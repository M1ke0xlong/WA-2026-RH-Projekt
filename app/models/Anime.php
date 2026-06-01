<?php
require_once '../app/models/Database.php';

class Anime {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Získá všechna anime z databáze
    // Načte všechna anime (s možností vyhledávání podle názvu)
    // Načte všechna anime (s chytrým vyhledáváním a anglickým názvem)
    // Načte všechna anime (s chytrým vyhledáváním a anglickým názvem)
    public function getAll($searchQuery = '') {
        $sql = "SELECT a.*, 
                       (SELECT AVG(score) FROM user_anime_list WHERE anime_id = a.id AND score IS NOT NULL) as avg_score,
                       (SELECT title FROM anime_titles WHERE anime_id = a.id AND type = 'english' LIMIT 1) as english_title
                FROM anime a ";
        
        // Použijeme dva odlišné zástupce (:search1 a :search2)
        if (!empty($searchQuery)) {
            $sql .= " WHERE a.primary_title LIKE :search1 
                      OR EXISTS (
                          SELECT 1 FROM anime_titles at 
                          WHERE at.anime_id = a.id AND at.title LIKE :search2
                      ) ";
        }
        
        $sql .= " ORDER BY a.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($searchQuery)) {
            $searchTerm = '%' . $searchQuery . '%';
            // Oběma zástupcům předáme to samé slovo
            $stmt->execute([
                ':search1' => $searchTerm,
                ':search2' => $searchTerm
            ]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll();
    }

    // Metoda pro vložení nového anime
    public function create($data) {
        try {
            // Začátek transakce
            $this->db->beginTransaction();

            // 1. Vložení do hlavní tabulky anime (přidali jsme :image)
            $sql = "INSERT INTO anime (primary_title, description, episodes, image, added_by) 
                    VALUES (:primary_title, :description, :episodes, :image, :added_by)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':primary_title' => $data['primary_title'],
                ':description'   => $data['description'] ?: null,
                ':episodes'      => $data['episodes'] ?: null,
                ':image'         => $data['image'] ?: null, // <--- TADY JE NOVÝ ŘÁDEK
                ':added_by'      => $_SESSION['user_id'] ?? null 
            ]);

            // Získáme ID právě vloženého anime
            $anime_id = $this->db->lastInsertId();

            // 2. Vložení alternativních názvů (pokud jsou vyplněné)
            $alt_sql = "INSERT INTO anime_titles (anime_id, type, title) VALUES (:anime_id, :type, :title)";
            $alt_stmt = $this->db->prepare($alt_sql);

            $alt_types = ['english', 'japanese', 'romaji'];
            foreach ($alt_types as $type) {
                if (!empty($data["title_$type"])) {
                    $alt_stmt->execute([
                        ':anime_id' => $anime_id,
                        ':type'     => $type,
                        ':title'    => $data["title_$type"]
                    ]);
                }
            }

            $this->db->commit();
            
            // OPRAVA: Vrátíme Controlleru to konkrétní číslo ID, ne jen "true"
            return $anime_id; 

        } catch (Exception $e) {
            // Pokud nastala chyba, vše vrátíme zpět
            $this->db->rollBack();
            return false;
        }
    }
    // Získá jedno konkrétní anime podle ID
    public function getById($id) {
        $sql = "SELECT a.*, 
                       u1.username as added_by_name, 
                       u2.username as updated_by_name,
                       (SELECT AVG(score) FROM user_anime_list WHERE anime_id = a.id AND score IS NOT NULL) as avg_score,
                       (SELECT COUNT(score) FROM user_anime_list WHERE anime_id = a.id AND score IS NOT NULL) as total_votes
                FROM anime a 
                LEFT JOIN users u1 ON a.added_by = u1.id 
                LEFT JOIN users u2 ON a.updated_by = u2.id 
                WHERE a.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Získá alternativní názvy pro dané anime
    public function getTitles($anime_id) {
        $sql = "SELECT type, title FROM anime_titles WHERE anime_id = :anime_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':anime_id' => $anime_id]);
        
        // Převedeme to do šikovného formátu ['english' => 'Název', 'japanese' => 'Název']
        $titles = [];
        while ($row = $stmt->fetch()) {
            $titles[$row['type']] = $row['title'];
        }
        return $titles;
    }
    // Získá všechny komentáře k danému anime
    // Načte všechny komentáře k danému anime (VČETNĚ AVATARU)
    public function getComments($anime_id) {
        // TADY JE ZMĚNA: Přidali jsme u.avatar
        $sql = "SELECT c.*, u.username, u.avatar 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.anime_id = :anime_id 
                ORDER BY c.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':anime_id' => $anime_id]);
        return $stmt->fetchAll();
    }

    // Přidá nový komentář
    public function addComment($anime_id, $user_id, $content) {
        $sql = "INSERT INTO comments (anime_id, user_id, content) VALUES (:anime_id, :user_id, :content)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':anime_id' => $anime_id,
            ':user_id'  => $user_id,
            ':content'  => $content
        ]);
    }
    // Smazání anime (kaskádově smaže i alternativní názvy a komentáře díky cizím klíčům)
    public function delete($id) {
        $sql = "DELETE FROM anime WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Úprava existujícího anime
    public function update($id, $data) {
        try {
            $this->db->beginTransaction();

            // 1. Update hlavní tabulky (PŘIDÁNO updated_by)
            $sql = "UPDATE anime SET 
                    primary_title = :primary_title, 
                    description = :description, 
                    episodes = :episodes, 
                    image = :image,
                    updated_by = :updated_by,
                    updated_at = NOW() 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':primary_title' => $data['primary_title'],
                ':description'   => $data['description'] ?: null,
                ':episodes'      => $data['episodes'] ?: null,
                ':image'         => $data['image'] ?: null,
                ':updated_by'    => $_SESSION['user_id'] ?? null, // <--- Uloží ID toho, kdo to právě upravuje
                ':id'            => $id
            ]);

            // 2. Update alternativních názvů (nejjednodušší je staré smazat a vložit nové)
            $del_sql = "DELETE FROM anime_titles WHERE anime_id = :id";
            $this->db->prepare($del_sql)->execute([':id' => $id]);

            $alt_sql = "INSERT INTO anime_titles (anime_id, type, title) VALUES (:anime_id, :type, :title)";
            $alt_stmt = $this->db->prepare($alt_sql);

            $alt_types = ['english', 'japanese', 'romaji'];
            foreach ($alt_types as $type) {
                if (!empty($data["title_$type"])) {
                    $alt_stmt->execute([
                        ':anime_id' => $id,
                        ':type'     => $type,
                        ':title'    => $data["title_$type"]
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    // Načte všechny obrázky galerie pro dané anime
    public function getGallery($anime_id) {
        $sql = "SELECT image_path FROM anime_gallery WHERE anime_id = :anime_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':anime_id' => $anime_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Uloží cestu k novému obrázku do galerie
    public function addGalleryImage($anime_id, $filename) {
        $sql = "INSERT INTO anime_gallery (anime_id, image_path) VALUES (:anime_id, :image_path)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':anime_id' => $anime_id, ':image_path' => $filename]);
    }
    // Načte jeden konkrétní komentář (pro kontrolu práv před editací/smazáním)
    public function getCommentById($comment_id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $comment_id]);
        return $stmt->fetch();
    }

    // Smaže komentář
    public function deleteComment($comment_id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $comment_id]);
    }

    // Upraví text komentáře
    public function updateComment($comment_id, $content) {
        $sql = "UPDATE comments SET content = :content WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':content' => $content, ':id' => $comment_id]);
    }
}