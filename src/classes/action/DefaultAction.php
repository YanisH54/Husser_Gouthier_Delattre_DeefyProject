<?php

namespace iutnc\deefy\action;

class DefaultAction extends Action {

    public function GET(): string {
        return <<<HTML
            <h2>Bienvenue sur Deefy</h2>
            <p>Deefy est une application qui vous permet de créer, organiser et écouter vos playlists musicales.</p>
            <p>Utilisez le menu pour accéder aux différentes fonctionnalités.</p>
        HTML;
    }

    public function POST(): string {
        return $this->GET();
    }
}
