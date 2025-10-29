<?php

namespace iutnc\deefy\audio\lists;



use iutnc\deefy\audio\tracks\AudioTrack;

class Playlist extends AudioList
{
    public function ajouterPiste(AudioTrack $at): void
    {
        $this->tabPistes[] = $at;
        $this->updateData();
    }

    public function retirerPiste(int $n): void
    {
        unset($this->tabPistes[$n]);
        $this->updateData();
    }

    public function ajouterPistes(array $liste): void
    {
        $this->tabPistes = array_merge($this->tabPistes,$liste);
        $this->updateData();
    }
}