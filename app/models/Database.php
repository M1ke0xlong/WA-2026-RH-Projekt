<?php

class Database {
    private $host = DB_HOST; 
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Vytvoření PDO instance s nastavením kódování utf8mb4
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Vyhazovat výjimky při chybách
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Výchozí návrat dat jako asociativní pole
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Použít nativní prepared statements (bezpečnost)
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            // Zastaví běh a vypíše chybovou hlášku, pokud spojení selže
            die("Chyba připojení k databázi: " . $exception->getMessage());
        }

        return $this->conn;
    }
}