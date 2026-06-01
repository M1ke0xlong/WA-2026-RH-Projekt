<?php
require_once '../app/models/Database.php';

class UserList {
    private $db; 

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Zjistí, zda a jak má uživatel anime uložené
    public function getEntry($user_id, $anime_id) {
        $sql = "SELECT * FROM user_anime_list WHERE user_id = :user_id AND anime_id = :anime_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':anime_id' => $anime_id]);
        return $stmt->fetch();
    }
    // Načte kompletní seznam uživatele, propojený s tabulkou anime
    public function getUserListEntries($user_id) {
        $sql = "SELECT ual.*, a.primary_title, a.image, a.episodes 
                FROM user_anime_list ual 
                JOIN anime a ON ual.anime_id = a.id 
                WHERE ual.user_id = :user_id 
                ORDER BY ual.updated_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    // Přidá nebo upraví záznam v uživatelově seznamu (tzv. UPSERT)
    public function saveEntry($user_id, $anime_id, $status, $score) {
        // Pokud skóre není vyplněné (nebo je 0), uložíme NULL
        $score = (!empty($score) && $score > 0) ? (int)$score : null;

        $sql = "INSERT INTO user_anime_list (user_id, anime_id, status, score) 
                VALUES (:user_id, :anime_id, :status, :score)
                ON DUPLICATE KEY UPDATE status = :update_status, score = :update_score";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'       => $user_id,
            ':anime_id'      => $anime_id,
            ':status'        => $status,
            ':score'         => $score,
            ':update_status' => $status,
            ':update_score'  => $score
        ]);
    }
}