<?php

namespace iutnc\deefy\classes\action;

use iutnc\deefy\classes\auth\AuthnProvider;
use iutnc\deefy\classes\repository\DeefyRepository;
use iutnc\deefy\classes\exception\AuthnException;

class SignUpAction extends Action
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

        $repo = new DeefyRepository();

        try {
            AuthnProvider::register($email, $passwd);
            return "Inscription réussie, vous pouvez maintenant vous connecter.";
        } catch (AuthnException $e) {
            return "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
