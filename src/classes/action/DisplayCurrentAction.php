<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\repository\DeefyRepository;

class DisplayCurrentAction extends Action
{
    public function execute(): string
    {
        // Vérifiez si l'utilisateur est connecté
        if (!isset($_SESSION['user_email'])) {
            return "Vous devez etre connecté pour afficher la playlist courante.";
        }

        // Vérifiez si une playlist est stockée en session
        if (!isset($_SESSION['playlist_id'])) {
            return "Aucune playlist courante.";
        }

        $playlistId = $_SESSION['playlist_id'];
        $repo = new DeefyRepository();
        $playlist = $repo->findPlaylistById($playlistId);

        // Vérifiez si la playlist existe
        if (!$playlist) {
            return "La playlist n'existe pas.";
        }

        // Affichage des informations de la playlist
        $html = "<h2>{$playlist['name']}</h2>";
        $html .= "<ul>";

        // Vérifiez si la playlist a des pistes
        if (empty($playlist['tracks'])) {
            $html .= "<li>Aucune piste dans cette playlist.</li>";
        } else {
            foreach ($playlist['tracks'] as $track) {
                $html .= "<li>{$track['title']} - {$track['artist']}</li>";
            }
        }
        $html .= "</ul>";

        // Ajoutez ici le formulaire pour ajouter une nouvelle piste
        $html .= "<h3>Ajouter une nouvelle piste</h3>";
        $html .= <<<FORM
<form method="POST" action="?action=add-track">
    <label for="title">Titre :</label>
    <input type="text" name="title" required><br>
    <label for="artist">Artiste :</label>
    <input type="text" name="artist" required><br>
    <button type="submit">Ajouter la piste</button>
</form>
FORM;

        return $html;
    }
}
