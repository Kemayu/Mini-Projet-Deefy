<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\render\AudioListRender;

class DisplayAction extends \iutnc\deefy\classes\action\Action
{
    public function execute(): string
    {
// Vérifiez si la playlist est en session
        if (isset($_SESSION['playlist'])) {
            $playlist = unserialize($_SESSION['playlist']);
            $renderer = new AudioListRender($playlist);

// Appel de la méthode render
// 1 pour mode compact, 2 pour mode complet
            return $renderer->render(2);
        } else {
            return "<div>Aucune playlist disponible.</div>";
        }
    }
}
