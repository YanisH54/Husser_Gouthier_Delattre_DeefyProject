<?php

namespace iutnc\deefy\render;

class PodcastTrackRenderer extends AudioTrackRenderer {

    public function render(int $selecteur) : string {
        $affichage = "<h3>Podcast</h3>";
        $affichage .= "<p>Titre : ".$this->at->__GET("titre")."</p>";
        $affichage .= "<p>Auteur : ".$this->at->__GET("auteur")."</p>";
        $affichage .= "<p>Genre : ".$this->at->__GET("genre")."</p>";
        $affichage .= "<p>Date : ".$this->at->__GET("date")."</p>";
        $affichage .= "<audio controls> <source src=audio/".$this->at->__GET("nomFichier"). "></audio>";
        return $affichage;

    }
}
