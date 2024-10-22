<?php

namespace iutnc\deefy\classes\action;

class DefaultAction extends Action
{
    public function execute(): string
    {
        return "<div>Default Action Executed</div>";
    }
}
