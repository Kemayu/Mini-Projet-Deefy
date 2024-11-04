<?php

// Configuration de la base de données
$host = 'localhost'; // Remplacez par l'hôte de votre base de données
$dbname = 'Deefy'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = ''; // Remplacez par votre mot de passe

try {
    // Créer une nouvelle connexion PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Configurer les attributs PDO pour gérer les erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Afficher un message de connexion réussie
    echo "Connexion réussie à la base de données : $dbname\n";

    // Insérer un utilisateur
    $email = 'utilisateur@example.com'; // Remplacez par l'email de l'utilisateur à ajouter
    $passwd = 'motdepasse'; // Remplacez par le mot de passe de l'utilisateur à ajouter
    $hashedPassword = password_hash($passwd, PASSWORD_DEFAULT); // Hash du mot de passe

    $insertStmt = $pdo->prepare("INSERT INTO User (email, passwd) VALUES (?, ?)");
    $insertStmt->execute([$email, $hashedPassword]);
    echo "Utilisateur inséré avec succès : $email\n";

    // Suppression de l'utilisateur
    $deleteEmail = 'utilisateur@example.com'; // Remplacez par l'email de l'utilisateur à supprimer
    $deleteStmt = $pdo->prepare("DELETE FROM User WHERE email = ?");
    $deleteStmt->execute([$deleteEmail]);
    echo "Utilisateur supprimé avec succès : $deleteEmail\n";

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
