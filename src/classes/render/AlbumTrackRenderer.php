<?php
namespace iutnc\deefy\render;


class AlbumTrackRenderer extends AudioTrackRenderer {


    public function render(int $selecteur) : string {
        $affichage = "<p>Titre : ".$this->at->__GET("titre")."</p>";
        $affichage.= "<p>Artiste : ".$this->at->__GET("auteur")."</p>";
        $affichage .= "<audio controls><source src=audio/".$this->at->__GET("nomFichier"). "></audio>";
        return $affichage;

    }
}