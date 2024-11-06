<?php

namespace iutnc\deefy\classes\auth;

use iutnc\deefy\classes\repository\DeefyRepository;

class AuthzProvider
{
    // Vérifie si l'utilisateur est authentifié par son email
    public static function authEmail(): bool
    {
        return isset($_SESSION['user_email']);
    }

    // Vérifie si l'utilisateur a un rôle particulier (ex : ADMIN)
    public static function checkUserRole(string $role): bool
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    // Vérifie si l'utilisateur est le propriétaire d'une playlist
    public static function isPlaylistOwner(int $userId, int $playlistId): bool
    {
        $repo = new DeefyRepository();
        // Vérifie si la playlist appartient bien à l'utilisateur
        $playlist = $repo->findPlaylistById($playlistId);

        // Si la playlist existe et que l'ID de l'utilisateur correspond à l'ID de l'utilisateur de la playlist
        return $playlist && $playlist['user_id'] === $userId;
    }

    // Méthode pour vérifier toutes les autorisations nécessaires en une seule fois
    public static function checkPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!self::$permission()) {
                return false;
            }
        }
        return true;
    }
}
