<?php



namespace iutnc\deefy\classes\render;

use iutnc\deefy\classes\audio\lists\AudioList;

class AudioListRender implements Renderer
{
    private AudioList $aL;

    public function __construct(AudioList $aL)
    {
        $this->aL = $aL;
    }

    public function render(int $selecteur): string
    {
        $s = "{$this->aL->nom}\n";
        foreach ($this->aL->liste as $e) {
            $s .= (new AlbumTrackRenderer($e))->render(Renderer::COMPACT);
        }
        return $s;
    }
}