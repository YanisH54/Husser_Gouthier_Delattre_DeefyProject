<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\PlaylistsAction;
use iutnc\deefy\action\SignInAction;
use iutnc\deefy\auth\AuthnProvider;

class Dispatcher {
    private string $action;

    public function __construct() {
        // "default" est une valeur arbitraire que l'on peut remplacer par ce que l'on veut
        // le but étant de ramener à la page d'accueil par la condition Default du switch
        $this->action = $_GET['action'] ?? "default";
    }

    public function run(): void {
        switch ($this->action) {
            case "add-playlist":
                $a = new AddPlaylistAction();
                break;
            case "display-playlist":
                $a = new DisplayPlaylistAction();
                break;
            case "add-track":
                $a = new AddTrackAction();
                break;
            case "add-user":
                $a = new AddUserAction();
                break;
            case "sign-in":
                $a = new SignInAction();
                break;
            case "playlists":
                $a = new PlaylistsAction();
                break;
            default:
                $a = new DefaultAction();
                break;
        }
        $html = $a->execute();
        // Si un utilisateur est connecté, on l'affiche au dessus du contenu
        if (isset($_SESSION['user'])) {
            $html = "<p>Connecté en tant que " . AuthnProvider::getSignedInUser() . "</p>" . $html;
        }

        $this->renderPage($html);
    }

    private function renderPage(string $html): void {
        $pageComplete = <<<END
            <!DOCTYPE html>
            <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Deefy - Gestion de musique en ligne</title>
                </head>
            
                <body>
                    <h1>Deefy</h1>
                    <p>Application de gestion de playlists musicales</p>
                    <hr>
            
                    <p>
                        <a href="?action=sign-in">Connexion</a> |
                        <a href="?action=add-user">Inscription</a>
                    </p>
            
                    <hr>
            
                    <h2>Menu principal</h2>
                    <ul>
                        <li><a href="?action=default">Accueil</a></li>
                        <li><a href="?action=add-playlist">Créer une playlist vide</a></li>
                        <li><a href="?action=add-track">Ajouter une piste</a></li>
                        <li><a href="?action=playlists">Mes playlists</a></li>
                        <li><a href="?action=display-playlist">Afficher la playlist courante</a></li>
                    </ul>
            
                    <hr>
            
                    <div>
                        $html
                    </div>
                </body>
            </html>
        END;

        echo $pageComplete;
    }

}
