<?php

namespace iutnc\deefy\classes\action;

class LogoutAction extends Action
{
    public function execute(): string
    {
// Détruire la session
        session_unset(); // Efface les variables de session
        session_destroy(); // Détruit la session

        return "Vous avez été déconnecté.";
    }
}
