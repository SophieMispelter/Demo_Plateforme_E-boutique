<?php
session_start(); // Création ou ouverture de session
// Première ligne de code, se positionne toujours en premier avant tout traitement php


// Connexion à la BDD 'boutique'
$pdo = new PDO('mysql:host=localhost;dbname=boutique', 'root', 'root',
    array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING
        )
);

// Définition d'une constante:
define( 'URL', 'http://localhost/Cours_Complets_WF3_2022/PHP/boutique/');
// Correspond à l'URL de notre site

// Définition des variables :
$content = ""; // variable prévue pour recevoir du contenu
$error = ""; // variable prévue pour recevoir les messages d'erreurs

// Inclusion des fonctions :
require_once "fonction.inc.php";
