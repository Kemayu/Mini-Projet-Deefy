<?php

namespace iutnc\deefy\classes\audio\tracks;
class AudioTrack
{
    protected string $titre;
    protected string $auteur;
    protected string $genre;
    protected int $duree;
    protected string $nomDuFichier;

    protected function __construct(string $titre, string $auteur, string $genre, string $nomDuFichier, int $duree)
    {
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->genre = $genre;
        $this->nomDuFichier = $nomDuFichier;
        $this->duree = $duree;
    }

    public function __toString(): string
    {
        return json_encode(get_object_vars($this));
    }

    public function __get(string $name): mixed
    {
        return (property_exists($this, $name)) ? $this->$name : throw new InvalidPropertyNameException("invalid property : $name");
    }

    public function setDuree(int $duree): void
    {
        $duree < 0 ? throw new InvalidPropertyValueException("invalid value : $duree") : $this->duree = $duree;
    }
}
