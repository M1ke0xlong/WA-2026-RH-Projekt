<?php
// core/Controller.php

class Controller {
    
    // Načtení modelu
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    // Načtení šablony (View) a předání dat
    public function view($view, $data = []) {
        // Z dat vytvoříme proměnné (např. pole ['anime' => 'Naruto'] se stane $anime)
        extract($data);
        
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die("Šablona " . $view . " neexistuje.");
        }
    }
    protected function setToast($message, $type = 'success') {
        // Uložíme zprávu do session, aby přežila přesměrování (redirect)
        $_SESSION['toast'] = [
            'message' => $message,
            'type'    => $type // 'success', 'error', 'info'
        ];
    }
}