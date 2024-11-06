<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\auth\AuthnProvider;
use iutnc\deefy\classes\auth\AuthzProvider;
use iutnc\deefy\classes\model\User;
use iutnc\deefy\classes\repository\DeefyRepository;

class DisplayAction extends Action
{
    public function execute(): string
    {
        if (!AuthnProvider::authEmail()) {
            return "Vous devez être connecté pour accéder à vos playlists";
        }

        // Charger l'utilisateur actuel
        $user = User::loadByEmail($_SESSION['user_email']);

        if (!$user) {
            return "Utilisateur non trouvé.";
        }

        $repo = new DeefyRepository();
        $playlists = $repo->findPlaylistsByUser($user->getId());

        $output = "<h1>Vos Playlists</h1>";
        foreach ($playlists as $playlist) {
            // Vérifier si l'utilisateur est propriétaire de la playlist avant de l'afficher
            if (AuthzProvider::isPlaylistOwner($user->getId(), $playlist['id'])) {
                $output .= "<p><a href='?action=current-playlist&id=" . htmlspecialchars($playlist['id']) . "'>" . htmlspecialchars($playlist['name']) . "</a></p>";
            }
        }

        return $output;
    }
}
