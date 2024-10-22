<?php

namespace iutnc\deefy\classes\audio\lists;

class Album extends AudioList
{
    protected string $nomArtiste;
    protected string $dateSortie;

    public function __construct(string $nom, array $liste = [])
    {
        parent::__construct($nom, $liste);
    }

    public function setArtiste(string $nom)
    {
        $this->nomArtiste = $nom;
    }

    public function setDate(string $nom)
    {
        $this->dateSortie = $nom;
    }
}

