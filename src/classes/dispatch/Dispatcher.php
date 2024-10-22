<?php

namespace iutnc\deefy\classes\dispatch;

use iutnc\deefy\classes\action\AddTrackAction;
use iutnc\deefy\classes\action\AddPlaylistAction;
use iutnc\deefy\classes\action\DefaultAction;
use iutnc\deefy\classes\action\DisplayAction;


class Dispatcher
{
    private ?string $action;

    public function __construct()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : null;
    }

    public function run(): void
    {
        switch ($this->action) {
            case "add-playlist":
                $a = new AddPlaylistAction();
                break;
            case "add-track":
                $a = new AddTrackAction();
                break;
            case "playlist":
                $a = new DisplayAction();
                break;
            case "default":
                $a = new DefaultAction();
                break;
            default:
                echo "erreur";
                return;
        }

        $html = $a->execute();
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        echo <<<END
        <!DOCTYPE html>
        <html>
            <head>
                <title>Deffy</title>
                <meta charset="UTF-8">
            </head>
            <body>
                <ul>
                    <li><a href="?action=add-playlist">Créer une nouvelle playlist</a></li>
                    <li><a href="?action=add-track">Ajouter une piste à la playlist</a></li>
                    <li><a href="?action=playlist">Afficher la playlist</a></li>
                    <li><a href="?action=add-user">Ajouter un utilisateur</a></li>
                    
                </ul>
                <p>$html</p>
            </body>
        </html>
        END;
    }
}
