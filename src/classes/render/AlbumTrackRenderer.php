<?php
namespace iutnc\deefy\render;


class AlbumTrackRenderer extends AudioTrackRenderer {


    public function render(int $selecteur) : string {
        $affichage = "<h3>Piste d'Album</h3>";
        $affichage .= "<p>Titre : ".$this->at->__GET("titre")."</p>";
        $affichage .= "<p>Auteur : ".$this->at->__GET("auteur")."</p>";
        $affichage .= "<p>Genre : ".$this->at->__GET("genre")."</p>";
        $affichage .= "<p>Annee : ".$this->at->__GET("annee")."</p>";
        $affichage .= "<p>Album : ".$this->at->__GET("album")."</p>";
        $affichage .= "<audio controls> <source src=audio/".$this->at->__GET("nomFichier"). "></audio>";
        return $affichage;
    }
}