<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AlbumTrack;

class AlbumTrackRenderer extends AudioTrackRenderer {


    public function render(int $selector) : string {
        $affichage = "<p>Titre : ".$this->at->__GET("titre")."</p>";
        $affichage.= "<p>Artiste : ".$this->at->__GET("auteur")."</p>";
        $affichage .= "<audio> ".$this->at->__GET("nomFichier"). "</audio>";
        return $affichage;

    }
}