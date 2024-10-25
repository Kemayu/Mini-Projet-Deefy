<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\classes\auth\AuthnProvider;
use iutnc\deefy\classes\render\AudioListRender;

class DisplayAction
{
    public function execute(int $id): void
    {
        try {
            AuthnProvider::checkPlaylistOwner($id);
            $repo = new DeefyRepository();
            $playlist = $repo->findPlaylistById($id);

            $renderer = new AudioListRender();
            $renderer->render($playlist);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
