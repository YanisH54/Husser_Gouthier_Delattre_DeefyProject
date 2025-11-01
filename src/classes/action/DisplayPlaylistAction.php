<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\Authz;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\Renderer;

class DisplayPlaylistAction extends ActionConnecte {

    public function GET(): string
    {
        if (!isset($_SESSION['playlist'])) {
            $html = "<b>Aucune playlist en session</b>";
        }
        else {
            $playlist = unserialize($_SESSION['playlist']);

            if (Authz::checkPlaylistCurrOwner($playlist->__GET("id"))){
                $renderer = new AudioListRenderer($playlist);
                $html = $renderer->render(Renderer::COMPACT);
            }
            else {
                $html = "<b>Erreur : Accès à cette playlist refusé</b>";
            }
        }
        return $html;
    }

    public function POST(): string
    {
        return $this->GET();
    }
}