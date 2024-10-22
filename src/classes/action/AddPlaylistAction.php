<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\audio\lists\Playlist;
use iutnc\deefy\classes\render\AudioListRender;

class AddPlaylistAction extends \iutnc\deefy\classes\action\Action
{
    public function execute(): string
    {
        if ($this->http_method === 'GET') {
            // Formulaire
            $html = <<<END
                <form method="post" action="?action=add-playlist">
                    <label>Nom de la playlist : <input type="text" name="nom" placeholder="Nom"></label>
                    <button type="submit">Créer</button>
                </form>
            END;
        } else {
            // Création de la playlist
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_SPECIAL_CHARS);

            if (!$nom) {
                return "<b>Nom de la playlist invalide</b>";
            }

            // Création de la playlist + stockage
            $playlist = new Playlist($nom);
            $_SESSION['playlist'] = serialize($playlist);

            $html = "<div>Playlist '$nom' créée.</div>";

            // Utilisation du renderer
            $a = new AudioListRender($playlist);

            // Sélectionnez le type d'affichage
            $html .= $a->render(1);  // 1 pour compact, 2 pour complet
        }
        return $html;
    }
}
