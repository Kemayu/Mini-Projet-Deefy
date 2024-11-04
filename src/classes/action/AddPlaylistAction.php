<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\repository\DeefyRepository;

class AddPlaylistAction extends Action
{
    public function execute(): string
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_email'])) {
            return "Vous devez etre connecté pour créer une playlist.";
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
<form method="POST" action="?action=add-playlist">
    <label for="name">Nom de la playlist :</label>
    <input type="text" name="name" required><br>
    <button type="submit">Créer la playlist</button>
</form>
END;
    }

    private function handleForm(): string
    {
        $name = $_POST['name'] ?? null;

        if (empty($name)) {
            return "Le nom de la playlist ne peut pas être vide.";
        }

        $repo = new DeefyRepository();

        // Récupérer l'ID utilisateur en fonction de l'email stocké en session
        $userId = $repo->getUserIdByEmail($_SESSION['user_email']);

        if ($userId === null) {
            return "Utilisateur non trouvé.";
        }

        // Passer l'ID utilisateur et le nom de la playlist
        $repo->createPlaylist($userId, $name);

        return "Playlist '$name' créée avec succès.";
    }

}
