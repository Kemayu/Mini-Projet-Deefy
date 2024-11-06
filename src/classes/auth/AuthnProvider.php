<?php

namespace iutnc\deefy\classes\auth;

use iutnc\deefy\classes\repository\DeefyRepository;
use iutnc\deefy\classes\exception\AuthnException;

class AuthnProvider
{

    public static function authEmail(): bool
    {
        return isset($_SESSION['user_email']);
    }

    public static function authPlaylist_id(): bool
    {
        return isset($_SESSION['playlist_id']);
    }

    public static function register(string $email, string $pass): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthnException("Erreur : Email invalide");
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
        $repo = new DeefyRepository();
        $repo->createUser($email, $hash);
    }

    public static function signin(string $email, string $passwd2check): void
    {
        $repo = new DeefyRepository();
        $user = $repo->getUserByEmail($email);

        if (!$user) {
            throw new AuthnException("Erreur d'authentification : utilisateur non trouvé");
        }

        if (!password_verify($passwd2check, $user['passwd'])) {
            throw new AuthnException("Erreur d'authentification : identifiants invalides");
        }

        // Authentification réussie
        $_SESSION['user_email'] = $email;
    }
}
