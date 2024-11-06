<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\auth\AuthzProvider;
use iutnc\deefy\classes\repository\DeefyRepository;

class AddTrackAction extends Action
{
    public function execute(): string
    {
        // Vérification de la connexion
        if (!AuthzProvider::authEmail()) {
            return "Vous devez être connecté pour ajouter une piste à une playlist.";
        }



        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $this->getForm();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleForm();
        }

        return "";
    }

    private function getForm(): string
    {
        return <<<END
<form method="POST" action="?action=add-track">
    <label for="title">Titre de la piste :</label>
    <input type="text" name="title" required><br>

    <label for="artist">Artiste :</label>
    <input type="text" name="artist" required><br>

    <label for="duration">Durée (en secondes) :</label>
    <input type="number" name="duration" required><br>

    <label for="genre">Genre :</label>
    <input type="text" name="genre"><br>

    <label for="file_name">Nom du fichier :</label>
    <input type="text" name="file_name" required><br>

    <button type="submit">Ajouter la piste</button>
</form>
END;
    }

    private function handleForm(): string
    {
        // Récupération des données du formulaire
        $title = $_POST['title'] ?? null;
        $artist = $_POST['artist'] ?? null;
        $duration = $_POST['duration'] ?? null;
        $genre = $_POST['genre'] ?? null;
        $file_name = $_POST['file_name'] ?? null;

        // Vérification des champs obligatoires
        if (empty($title) || empty($artist) || empty($duration) || empty($file_name)) {
            return "Tous les champs sont requis, excepté le genre.";
        }

        // Validation de la durée
        if (!is_numeric($duration) || $duration <= 0) {
            return "La durée doit être un nombre positif.";
        }


        if (!empty($genre) && strlen($genre) > 50) {
            return "Le genre ne peut pas dépasser 50 caractères.";
        }

        // Récupération de l'ID de la playlist courante
        $playlistId = $_SESSION['playlist_id'];
        $repo = new DeefyRepository();

        // Sauvegarde de la piste dans la base de données
        $trackId = $repo->saveTrack($title, $artist, $duration, $genre, $file_name);

        // Ajout de la piste à la playlist
        $repo->addTrackToPlaylist($playlistId, $trackId);

        return "La piste a été ajoutée avec succès à la playlist.";
    }
}
