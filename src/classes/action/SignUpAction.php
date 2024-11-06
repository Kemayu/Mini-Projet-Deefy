<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\auth\AuthzProvider;
use iutnc\deefy\classes\repository\DeefyRepository;

class SignUpAction extends Action
{
    public function execute(): string
    {
        // Vérification connexion
        if (AuthzProvider::authEmail()) {
            return "Vous êtes déjà connecté. Veuillez vous déconnecter avant de créer un nouveau compte";
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
<form method="POST" action="?action=signup">
    <label for="email">Email :</label>
    <input type="email" name="email" required><br>
    <label for="passwd">Mot de passe :</label>
    <input type="password" name="passwd" required><br>
    <button type="submit">S'inscrire</button>
</form>
END;
    }

    private function handleForm(): string
    {
        $email = $_POST['email'] ?? null;
        $passwd = $_POST['passwd'] ?? null;

        if (empty($email) || empty($passwd)) {
            return "L'email et le mot de passe sont obligatoires";
        }

        $repo = new DeefyRepository();
        $hashedPassword = password_hash($passwd, PASSWORD_DEFAULT);
        $repo->createUser($email, $hashedPassword);

        return "Inscription réussie. Vous pouvez maintenant vous connecter";
    }
}
