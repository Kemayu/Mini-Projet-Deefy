<?php

namespace iutnc\deefy\classes\dispatch;

use iutnc\deefy\classes\action\AddTrackAction;
use iutnc\deefy\classes\action\AddPlaylistAction;
use iutnc\deefy\classes\action\DisplayCurrentAction;
use iutnc\deefy\classes\action\DisplayAction;
use iutnc\deefy\classes\action\LogInAction;
use iutnc\deefy\classes\action\SignUpAction;
use iutnc\deefy\classes\action\LogoutAction;

class Dispatcher
{
    private ?string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? null;
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
            case "current-playlist":
                $a = new DisplayCurrentAction();
                break;
            case "my-playlists":
                $a = new DisplayAction();
                break;
            case "signup":
                $a = new SignUpAction();
                break;
            case "signin":
                $a = new LogInAction();
                break;
            case "logout":
                $a = new LogoutAction();
                break;
            default:
                $this->renderPage("Bienvenue sur Deefy ! Connectez-vous ou inscrivez-vous pour gérer vos playlists");
                return;
        }

        $html = $a->execute();
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        echo <<<END
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <title>Deefy</title>
                <meta charset="UTF-8">
                <link rel="stylesheet" href="css/style.css">
            </head>
            <body>
             <div class="container">
                <ul>
                    <li><a href="?action=my-playlists">Mes playlists</a></li>
                    <li><a href="?action=add-playlist">Créer une nouvelle playlist</a></li>
                    <li><a href="?action=current-playlist">Afficher la playlist courante</a></li>
                    <li><a href="?action=signup">S'inscrire</a></li>
                    <li><a href="?action=signin">S'authentifier</a></li>
                    <li><a href="?action=logout">Se déconnecter</a></li>
                </ul>
                <p>$html</p>
                 </div>
            </body>
        </html>
        END;
    }
}
