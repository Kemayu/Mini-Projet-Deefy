<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\auth\AuthnProvider;
use iutnc\deefy\classes\repository\DeefyRepository;
use iutnc\deefy\classes\exception\AuthnException;

class LogInAction extends Action
{
    public function execute(): string
    {
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
<form method="POST" action="?action=signin">
    <label for="email">Email :</label>
    <input type="email" name="email" required><br>
    <label for="passwd">Mot de passe :</label>
    <input type="password" name="passwd" required><br>
    <button type="submit">S'authentifier</button>
</form>
END;
    }

    private function handleForm(): string
    {
        $email = $_POST['email'] ?? null;
        $passwd = $_POST['passwd'] ?? null;

        try {
            AuthnProvider::signin($email, $passwd);
            $_SESSION['user_email'] = $email;


            $repo = new DeefyRepository();
            $userId = $repo->getUserIdByEmail($email);

            $lastPlaylist = $repo->findLastPlaylistByUser($userId);
            if ($lastPlaylist) {
                $_SESSION['playlist_id'] = $lastPlaylist['id'];
            }

            return "Connexion réussie. Vous pouvez maintenant créer ou consulter vos playlists";
        } catch (AuthnException $e) {
            session_unset();
            session_destroy();
            return $e->getMessage();
        }
    }
}
