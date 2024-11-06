<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\auth\AuthzProvider;
use iutnc\deefy\classes\repository\DeefyRepository;

class DisplayCurrentAction extends Action
{
    public function execute(): string
    {
        // Vérification
        if (!AuthzProvider::authEmail()) {
            return "Vous devez etre connecté pour créer une playlist";
        }

        if (!isset($_SESSION['playlist_id'])){
            return "Vous n'avez pas de playlist courante";
        }
        $repo = new DeefyRepository();
        if (isset($_GET['id'])) {
            $_SESSION['playlist_id'] = $_GET['id'];
        }
        $playlist = $repo->findPlaylistById($_SESSION['playlist_id']);


        // Vérifiez si la playlist existe
        if (!$playlist) {
            return "La playlist n'existe pas";
        }

        // Affichage des informations de la playlist
        $html = "<h2>{$playlist['name']}</h2>";

        // Ajoutez les autres informations de la playlist, si nécessaire
        $html .= "<p><strong>Créée le :</strong> " . $playlist['created_at'] . "</p>";

        // Liste des pistes de la playlist
        $html .= "<ul>";

        // Vérifiez si la playlist a des pistes
        if (empty($playlist['tracks'])) {
            $html .= "<li>Aucune piste dans cette playlist.</li>";
        } else {
            foreach ($playlist['tracks'] as $track) {
                // Assurez-vous d'afficher les attributs supplémentaires pour chaque piste
                $html .= "<li>";
                $html .= "<strong>Titre :</strong> {$track['title']}<br>";
                $html .= "<strong>Artiste :</strong> {$track['artist']}<br>";
                $html .= "<strong>Durée :</strong> {$track['duration']} secondes<br>";

                // Si le genre est disponible, affichez-le
                if (!empty($track['genre'])) {
                    $html .= "<strong>Genre :</strong> {$track['genre']}<br>";
                } else {
                    $html .= "<strong>Genre :</strong> Non spécifié<br>";
                }

                // Si le nom du fichier est disponible, affichez-le
                if (!empty($track['file_name'])) {
                    $html .= "<strong>Fichier :</strong> {$track['file_name']}<br>";
                } else {
                    $html .= "<strong>Fichier :</strong> Non disponible<br>";
                }

                $html .= "</li><br>";
            }
        }

        $html .= "</ul>";


        // Fourmulaire
        $html .= "<h3>Ajouter une nouvelle piste</h3>";
        $html .= <<<END
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

        return $html;
    }
}
