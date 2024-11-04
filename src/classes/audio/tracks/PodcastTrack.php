<?php


namespace iutnc\deefy\classes\audio\tracks;

class PodcastTrack extends AudioTrack
{
    protected string $date;

    public function __construct(string $titre, string $auteur, string $genre, string $nomDuFichier, int $duree, string $date)
    {
        parent::__construct($titre, $auteur, $genre, $nomDuFichier, $duree);
        $this->date = $date;
    }

}