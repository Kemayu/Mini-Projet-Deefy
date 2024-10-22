<?php

namespace iutnc\deefy\classes\render;
interface Renderer
{

    const LONG = 2;
    const COMPACT = 1;

    public function render(int $selecteur): string;
}

{

}