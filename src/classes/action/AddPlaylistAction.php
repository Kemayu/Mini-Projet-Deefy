<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\audio\lists\Playlist;
use iutnc\deefy\classes\render\AudioListRender;

class AddPlaylistAction extends \iutnc\deefy\classes\action\Action
{
    public function execute(): string
    {
        if ($this->http_method === 'GET') {
            // Formulaire de création de playlist
            $html = <<<END
                <form method="post" action="?action=add-playlist">
                    <label>Nom de la playlist : <input type="text" name="nom" placeholder="Nom"></label>
                    <button type="submit">Créer</button>
                </form>
            END;
        } else {
            // Création de la playlist avec le nom récupéré du POST
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_SPECIAL_CHARS);

            if (!$nom) {
                return "<b>Nom de la playlist invalide</b>";
            }

            // Création de la playlist et stockage dans la session
            $playlist = new Playlist($nom);
            $_SESSION['playlist'] = serialize($playlist);

            $html = "<div>Playlist '$nom' créée.</div>";

            // Utilisation du renderer pour afficher la playlist (sélectionnez compact ou complet)
            $a = new AudioListRender($playlist);

            // Sélectionnez le type d'affichage - ici nous utilisons le mode compact (1)
            $html .= $a->render(1);  // 1 pour compact, 2 pour complet
        }
        return $html;
    }
}
