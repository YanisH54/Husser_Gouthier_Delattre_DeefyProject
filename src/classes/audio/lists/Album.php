<?php

namespace iutnc\deefy\audio\lists;

class Album extends AudioList
{

    protected string $artiste,$date;

    public function __construct(string $nom, array $tab){
        parent::__construct($nom,$tab);
    }

    public function setArtiste(string $nouvArtiste){
        $this->artiste=$nouvArtiste;
    }

    public function setDate(string $date)
    {
        $this->date = $date;
    }

}