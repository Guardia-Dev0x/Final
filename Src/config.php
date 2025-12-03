<?php
// Fichier: config.php

// Paramètres de connexion à la base de données
define('DB_SERVER', 'localhost'); // L'hôte de votre base de données (souvent 'localhost')
define('DB_USERNAME', 'root'); // Votre nom d'utilisateur MySQL
define('DB_PASSWORD', ''); // Votre mot de passe MySQL
define('DB_NAME', 'guardia'); // Le nom de la base de données que vous utilisez

try {
    // Connexion à la base de données en utilisant PDO (PHP Data Objects)
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    
    // Configure PDO pour lancer des exceptions en cas d'erreur (bonne pratique)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Afficher un message d'erreur si la connexion échoue
    die("ERREUR: Impossible de se connecter à la base de données. " . $e->getMessage());
}
?>