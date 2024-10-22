<?php

namespace iutnc\deefy\classes\audio\lists;

class Playlist extends AudioList
{
    public function __construct(string $nom, array $liste = [])
    {
        parent::__construct($nom, $liste);
    }

    public function ajout(AudioTrack $audioTrack): void
    {
        $this->nbPiste += 1;
        $this->dureeTotal += $audioTrack->duree;
        $this->liste[] = $audioTrack;
    }

    public function supprime(int $indice): void
    {
        $this->nbPiste -= 1;
        $this->dureeTotal -= $this->liste[$indice]->duree;
        unset($this->liste[$indice]);

    }

    public function ajoutAll(array $tab)
    {
        $this->liste = array_unique(array_merge($this->liste, $tab));
        $this->nbPiste = count($this->liste);
        $this->dureeTotal = 0;
        foreach ($this->liste as $e) {
            $this->dureeTotal += $e->duree;
        }
    }
}