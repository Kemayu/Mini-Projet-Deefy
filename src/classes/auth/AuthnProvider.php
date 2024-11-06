<?php

namespace iutnc\deefy\classes\auth;

use iutnc\deefy\classes\repository\DeefyRepository;
use iutnc\deefy\classes\exception\AuthnException;

class AuthnProvider
{
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

        // Authentification réussie - Stocke l'email et le rôle en session
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user['role']; // Stocker le rôle de l'utilisateur en session
    }
}
