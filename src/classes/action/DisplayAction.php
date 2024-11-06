<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\auth\AuthzProvider;
use iutnc\deefy\classes\model\User;
use iutnc\deefy\classes\repository\DeefyRepository;
use iutnc\deefy\classes\exception\AuthnException;

class DisplayAction extends Action
{
    public function execute(): string
    {
        // Vérification de l'authentification de l'utilisateur
        if (!AuthzProvider::authEmail()) {
            return "Vous devez être connecté pour accéder à vos playlists.";
        }

        // Chargement de l'utilisateur à partir de son email en session
        $user = User::loadByEmail($_SESSION['user_email']);
        if (!$user) {
            return "Utilisateur non trouvé.";
        }

        // Création du repository pour récupérer les playlists de l'utilisateur
        $repo = new DeefyRepository();
        $playlists = $repo->findPlaylistsByUser($user->getId());

        // Générer l'affichage des playlists
        $output = "<h1>Vos Playlists</h1>";

        foreach ($playlists as $playlist) {
            // Vérifier si l'utilisateur est bien propriétaire de la playlist
            if (AuthzProvider::isPlaylistOwner($user->getId(), $playlist['id'])) {
                $output .= "<p><a href='?action=current-playlist&id=" . htmlspecialchars($playlist['id']) . "'>"
                    . htmlspecialchars($playlist['name']) . "</a></p>";
            }
        }

        return $output;
    }
}
