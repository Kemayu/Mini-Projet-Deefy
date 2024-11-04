<?php

declare(strict_types=1);

use iutnc\deefy\classes\repository\DeefyRepository;

ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

// Configuration de la base de données
DeefyRepository::setConfig(__DIR__ . '/config/config.ini'); // Assurez-vous que ce fichier existe et est correct

// Connexion à la base de données
$host = 'localhost'; // Remplacez par votre hôte
$dbname = 'Deefy'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = ''; // Remplacez par votre mot de passe

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialiser AuthnProvider avec PDO
    \iutnc\deefy\classes\auth\AuthnProvider::init($pdo);

    session_start();

    $d = new iutnc\deefy\classes\dispatch\Dispatcher();
    $d->run();

} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
