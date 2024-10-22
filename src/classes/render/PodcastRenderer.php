<?php


namespace iutnc\deefy\classes\render;

use iutnc\deefy\classes\audio\tracks\PodcastTrack;

class PodcastRenderer implements Renderer
{
    public PodcastTrack $pT;

    public function __construct(PodcastTrack $pT)
    {
        $this->pT = $pT;
    }

    public function render(int $selector): string
    {
        switch ($selector) {
            case 1:
                $s = $this->compact();
                break;
            case 2:
                $s = $this->complet();
                break;
            default:
                throw new InvalidPropertyValueException("invalid value : $selector");
        }
        return $s;
    }

    private function compact(): string
    {
        return
            "<p>Titre :{$this->pT->__get("titre")} 
             <br>Nom du fichier : {$this->pT->__get("nomDuFichier")}</p>";
    }

    private function complet(): string
    {
        return
            "<p>Titre :   {$this->pT->__get("titre")}
             <br>date :   {$this->pT->__get("date")} 
             <br>Auteur :   {$this->pT->__get("auteur")} 
             <br>Nom du fichier :  {$this->pT->__get("nomDuFichier")} 
             <br>Genre :   {$this->pT->__get("genre")}</p>";
    }
}