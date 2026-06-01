<?php
// core/App.php

class App {
    // Výchozí hodnoty, když uživatel přijde na hlavní stránku
    protected $controller = 'AnimeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Zjištění Controlleru (např. url=user/login -> UserController)
        if (isset($url[0])) {
            $potentialController = ucfirst($url[0]) . 'Controller';
            if (file_exists('../app/controllers/' . $potentialController . '.php')) {
                $this->controller = $potentialController;
                unset($url[0]);
            }
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Zjištění Metody (např. url=anime/detail -> metoda detail)
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Parametry (např. url=anime/detail/5 -> parametr 5)
        $this->params = $url ? array_values($url) : [];

        // Zavolání controlleru, metody a předání parametrů
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Funkce pro rozsekání URL z $_GET['url']
    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}