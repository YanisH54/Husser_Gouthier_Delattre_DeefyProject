<?php

namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\PodcastTrack;

class PodcastTrackRenderer extends AudioTrackRenderer {

    public function render(int $selecteur) : string {
        $affichage = "<p>".$this->at->__GET("titre")."</p>";
        $affichage .= "<audio> ".$this->at->__GET("nomFichier"). "</audio>";
        return $affichage;

    }
}
