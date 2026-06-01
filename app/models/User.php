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
        // Heslo nikdy neukládáme v čistém textu!
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $this->db->prepare($sql);
        
        try {
            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $hash
            ]);
        } catch (PDOException $e) {
            // Chytne chybu, pokud např. uživatel se stejným jménem už existuje (kvůli UNIQUE v databázi)
            return false; 
        }
    }

    // Přihlášení uživatele
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // Pokud uživatel existuje a heslo sedí s hashem v databázi
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
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
}