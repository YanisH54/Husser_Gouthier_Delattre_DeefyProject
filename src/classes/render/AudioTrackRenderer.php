<?php


namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\AudioTrack;

abstract class AudioTrackRenderer implements Renderer{
    protected AudioTrack $at;

    public function __construct(AudioTrack $a){
        $this->at=$a;
    }


}
