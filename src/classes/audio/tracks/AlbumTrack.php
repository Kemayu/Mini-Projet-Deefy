<?php

namespace iutnc\deefy\classes\audio\tracks;



class AlbumTrack extends AudioTrack
{
    protected string $numeroDePiste;
    protected string $album;

    public function __construct(string $titre, string $auteur, string $genre, string $nomDuFichier, int $duree, string $album, int $numeroDePiste)
    {
        parent::__construct($titre, $auteur, $genre, $nomDuFichier, $duree);
        $this->album = $album;
        $this->numeroDePiste = $numeroDePiste;
    }

}