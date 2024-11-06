<?php

namespace iutnc\deefy\classes\action;

class LogoutAction extends Action
{
    public function execute(): string
    {
        session_unset();
        session_destroy();
        return "Vous avez été déconnecté";
    }
}
