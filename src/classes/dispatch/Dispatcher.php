<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\PlaylistsAction;
use iutnc\deefy\action\SignInAction;

class Dispatcher {
    private string $action;

    public function __construct() {
        $this->action = $_GET['action'] ?? "default";
    }

    public function run(): void{
        $a = match ($this->action) {
            "add-playlist" => new AddPlaylistAction(),
            "display-playlist" => new DisplayPlaylistAction(),
            "add-track" => new AddTrackAction(),
            "add-user" => new AddUserAction(),
            "sign-in" => new SignInAction(),
            "playlists" => new PlaylistsAction(),
            "default" => new DefaultAction()
        };
        $html = $a->execute();
        if ($this->action !== "default"){
            $html .= "<br><p><a href='?action=default'>Retourner à l'accueil</a></p>";
        }
        $this->renderPage($html);
    }

     private function renderPage(string $html): void{
        http_response_code(201);
        header("Content-Type: text/html");
        header("Content-size: " . strlen($html));
        echo ($html);
     }
}
