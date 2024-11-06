<?php

namespace iutnc\deefy\classes\auth;

use PDO;
use iutnc\deefy\classes\exception\AuthnException;

class AuthnProvider
{
    private static PDO $db;

    public static function init(PDO $pdo)
    {
        self::$db = $pdo;
    }

    public static function register(string $email, string $pass): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthnException("Error: Invalid email format");
        }

        // Hashage
        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);

        // Insertion
        $stmt = self::$db->prepare("INSERT INTO User (email, passwd) VALUES (?, ?)");
        $stmt->execute([$email, $hash]);
    }


    public static function signin(string $email, string $passwd2check): void
    {

        $stmt = self::$db->prepare("SELECT passwd FROM User WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        if (!$row) {
            throw new AuthnException("Auth error: Utilisateur non trouvé");
        }

        $hash = $row['passwd'];

// Vérification
        if (!password_verify($passwd2check, $hash)) {
            throw new AuthnException("Auth error: Invalid credentials");
        }
    }

}
