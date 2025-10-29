<?php

namespace iutnc\deefy\render;



use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;

class AudioListRenderer implements Renderer
{
    protected AudioList $al;
    public function __construct(AudioList $al)
    {
        $this->al = $al;
    }

    function render(int $selecteur): string
    {
        $affichage = "<p>Nom playlist :".$this->al->__GET('nom')."</p>";
        foreach ($this->al->__GET("tabPistes") as $audioTrack){
            if ($audioTrack instanceof AlbumTrack)
                $renderer = new AlbumTrackRenderer($audioTrack);
            else
                $renderer = new PodcastTrackRenderer($audioTrack);
            $affichage .= $renderer->render(self::COMPACT);
        }
        $affichage .= "<p>Nombre de piste : ".$this->al->__GET("nbrPistes")."</p>";
        $affichage .= "<p>Durée totale : ".$this->al->__GET("dureeTotale")."</p>";
        return $affichage;
    }
}