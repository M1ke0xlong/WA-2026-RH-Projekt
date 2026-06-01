<?php
// public/index.php

// Spuštění session (pro přihlašování a flash messages)
session_start();

// Načtení konfigurace a jádra
require_once '../config/config.php';
require_once '../core/App.php';
require_once '../core/Controller.php';

// Inicializace aplikace (Routeru)
$app = new App(); 