<?php
require_once '../app/models/Database.php';

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Registrace nového uživatele
    public function register($username, $email, $password) {
        // OPRAVA: Heslo už nám z AuthControlleru přichází bezpečně zašifrované.
        // Smažeme řádek, který dělal druhou šifru, a použijeme rovnou parametr $password.
        
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $this->db->prepare($sql);
        
        try {
            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $password // <-- Tady posíláme přímo už zašifrované heslo
            ]);
        } catch (PDOException $e) {
            // Chytne chybu, pokud např. uživatel se stejným jménem už existuje
            return false; 
        }
    }

    // Přihlášení uživatele
    public function login($username, $password) {
        // 1. Najdeme uživatele pouze podle jména
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // Pokud uživatel existuje, zkontrolujeme heslo
        if ($user) {
            // A) Zkouška pro nová, bezpečně zašifrovaná hesla
            // OPRAVA: Změněno na $user['password_hash'] podle tvé databáze
            if (password_verify($password, $user['password_hash'])) {
                return $user;
            }
            
            // B) Záchrana pro stará hesla v čistém textu (kdyby náhodou)
            // OPRAVA: Změněno na $user['password_hash']
            if ($password === $user['password_hash']) {
                return $user;
            }
        }

        return false;
    }
    // Získá data uživatele podle ID
    public function getById($id) {
        $sql = "SELECT id, username, email, role, avatar FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Aktualizuje avatar uživatele
    public function updateAvatar($id, $filename) {
        $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':avatar' => $filename, ':id' => $id]);
    }
    // Smazání uživatele z databáze (včetně jeho avataru, pokud nějaký má)
    public function deleteUser($id) {
        // Nejprve zkusíme najít jeho avatara, abychom nezanechávali zbytečné soubory
        $sqlSelect = "SELECT avatar FROM users WHERE id = :id";
        $stmtSelect = $this->db->prepare($sqlSelect);
        $stmtSelect->execute([':id' => $id]);
        $user = $stmtSelect->fetch();

        if ($user && !empty($user['avatar'])) {
            $avatarPath = '../public/uploads/avatars/' . $user['avatar'];
            if (file_exists($avatarPath)) {
                unlink($avatarPath); // Smaže fyzický soubor obrázku
            }
        }

        // Fyzické smazání záznamu z databáze
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    // Načte všechny uživatele pro administrátorský panel
    public function getAllUsers() {
        $sql = "SELECT id, username, email, role, avatar, created_at FROM users ORDER BY role ASC, created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
}