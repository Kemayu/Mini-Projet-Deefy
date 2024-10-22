<?php

namespace iutnc\deefy\classes\render;


use iutnc\deefy\classes\audio\tracks\AlbumTrack;

class AlbumTrackRenderer implements Renderer
{
    public AlbumTrack $aT;

    public function __construct(AlbumTrack $aT)
    {
        $this->aT = $aT;
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
            "<p>Titre :{$this->aT->__get("titre")} 
             <br>Nom du fichier : {$this->aT->__get("nomDuFichier")}</p>";
    }

    private function complet(): string
    {
        return
            "<p>Titre :   {$this->aT->__get("titre")}
             <br>Album :   {$this->aT->__get("album")} 
             <br>Auteur :   {$this->aT->__get("auteur")} 
             <br>Numero de Piste :   {$this->aT->__get("numeroDePiste")} 
             <br>Nom du fichier :  {$this->aT->__get("nomDuFichier")} 
             <br>Genre :   {$this->aT->__get("genre")}</p>";
    }
}