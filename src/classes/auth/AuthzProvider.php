<?php

namespace iutnc\deefy\classes\auth;

use iutnc\deefy\classes\model\User;
use iutnc\deefy\classes\repository\DeefyRepository;

class AuthzProvider
{
    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     */
    public static function hasRole(User $user, string $role): bool
    {
        return $user->getRole() === $role;
    }

    /**
     * Vérifie si la playlist appartient bien à l'utilisateur
     */
    public static function isPlaylistOwner(int $userId, int $playlistId): bool
    {
        $repo = new DeefyRepository();
        $playlist = $repo->findPlaylistById($playlistId);

        return $playlist && $playlist['user_id'] === $userId;
    }
}
