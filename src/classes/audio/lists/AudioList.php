<?php

namespace iutnc\deefy\classes\audio\lists;
class AudioList
{
    protected string $nom;
    protected array $liste;
    protected int $nbPiste;
    protected int $dureeTotal;

    public function __construct(string $nom, array $liste = [])
    {
        $this->dureeTotal = 0;
        $this->nom = $nom;
        $this->liste = $liste;
        $this->nbPiste = count($liste);
        foreach ($liste as $e) {
            $this->dureeTotal += $e->__get("duree");
        }
    }

    public function __get(string $name)
    {
        return (property_exists($this, $name)) ? $this->$name : throw new InvalidPropertyNameException("invalid property : $name");
    }


}