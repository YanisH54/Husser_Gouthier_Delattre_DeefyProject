<?php

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack {

    protected string $date;



    public function __construct(string $t, string $nF,string $auteur,int $n,string $genre, string $date){
        parent::__construct($t, $nF,$auteur,$n, $genre);
        $this->date = $date;
    }

    public function getType(): string
    {
        return parent::$PODCAST;
    }
}
