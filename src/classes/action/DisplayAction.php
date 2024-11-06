<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\repository\DeefyRepository;

class DisplayAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['user_email'])) {
            return "Vous devez être connecté pour voir vos playlists";
        }

        $repo = new DeefyRepository();

// Récupérer l'ID de l'utilisateur
        $userId = $repo->getUserIdByEmail($_SESSION['user_email']);

        if ($userId === null) {
            return "Utilisateur non trouvé";
        }

        $playlists = $repo->findPlaylistsByUser($userId);

// Affichage des playlists
        $output = "<h1>Vos Playlists</h1>";
        foreach ($playlists as $playlist) {
            $output .= "<p><a href='?action=current-playlist&id=" . htmlspecialchars($playlist['id']) . "'>" . htmlspecialchars($playlist['name']) . "</a></p>";
        }

        return $output;
    }
}
