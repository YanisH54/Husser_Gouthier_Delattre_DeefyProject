<?php

namespace iutnc\deefy\action;

class DefaultAction extends Action {

    public function GET() : string{
        return <<<END
            <br>Bienvenue !</br>
            <nav>
            <a href="?action=add-user">Créer un compte</a>
            <a href="?action=sign-in">Se connecter</a>
            <a href="?action=playlists">Voir mes playlist</a>
            <a href="?action=add-playlist">Créer une playlist</a>
            <a href="?action=add-track">Ajouter une piste</a>
            <a href="?action=display-playlist">Afficher la playlist récente</a>
            </nav>
            END;
    }

    public function POST(): string
    {
        return $this->GET();
    }
}