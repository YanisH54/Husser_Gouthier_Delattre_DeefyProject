<?php

namespace iutnc\deefy\audio\tracks;
class AlbumTrack extends AudioTrack {

    protected string $album;
    protected int $annee,$numPiste;

    public function __construct(string $t, string $nF,string $auteur,int $duree, string $a,string $n,string $genre, int $annee){
        parent::__construct($t,$nF,$auteur,$duree,$genre);
        $this->album = $a;
        $this->numPiste = $n;
        $this->annee = $annee;
    }

    public function getType(): string
    {
        return parent::$ALBUM;
    }


}