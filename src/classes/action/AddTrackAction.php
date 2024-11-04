<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\repository\DeefyRepository;

class AddTrackAction extends Action
{
    public function execute(): string
    {
// Vérification connexion
        if (!isset($_SESSION['user_email'])) {
            return "Vous devez vous authentifier pour ajouter une piste.";
        }

// Vérification playlist
        if (!isset($_SESSION['playlist_id'])) {
            return "Aucune playlist courante sélectionnée.";
        }

        $title = $_POST['title'] ?? null;
        $artist = $_POST['artist'] ?? null;

        if (empty($title) || empty($artist)) {
            return "Le titre et l'artiste sont requis.";
        }

        $playlistId = $_SESSION['playlist_id'];
        $repo = new DeefyRepository();
        $trackId = $repo->saveTrack($title, $artist);
        $repo->addTrackToPlaylist($playlistId, $trackId);

        return "La piste a été ajoutée avec succès à la playlist.";
    }
}
