<?php

namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioTrack {

    static string $PODCAST = "PODCAST";
    static string $TRACK = "AUDIOTRACK";
    static string $ALBUM = "ALBUM";


    protected string $titre,$auteur,$genre,$nomFichier;
    protected int $duree;
    protected ?int $id;

    public function __construct(string $t, string $nF,string $a,int $n,string $genre){
        $this->nomFichier = $nF;
        $this->titre = $t;
        $this->auteur = $a;
        $this->duree = $n;
        $this->genre = $genre;
        $this->id = null;
    }

    public function __toString(){
        return json_encode(get_object_vars($this));
    }

    public function __GET(string $attribut) {
        if ( property_exists ($this, $attribut) )
            return $this->$attribut;
        else
            throw new InvalidPropertyNameException();
    }

    public function setId(int $n): void
    {
        $this->id = $n;
    }

    public function getType() : string {
        return self::$TRACK;
    }




}