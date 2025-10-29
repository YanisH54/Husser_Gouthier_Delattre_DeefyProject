<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\repository\DeefyRepository;

class AddPlaylistAction extends ActionConnecte {


    public function GET(): string
    {
        return <<<END
                <form method='post' action=?action=add-playlist><br>
                Nom de la playlist : <input type='text' name='nom'><br>
                <button type="submit">Valider</button> </form>";
        END;

    }

    public function POST(): string
    {
        $nom = $_POST['nom'];
        if (!filter_var($nom, FILTER_SANITIZE_SPECIAL_CHARS)) {
            $html = <<<END
                    <b>Nom de playlist incorrect</b><br>
                    <a href="?action=add-playlist">Récreer une nouvelle playlist</a>
                    END;
        } else {
            $pl = new Playlist($nom);
            $_SESSION['playlist'] = serialize($pl);
            $pdo = DeefyRepository::getInstance();
            $pdo->sauvegarderNouvellePlaylist($pl);
            $pdo->saveUserPlaylist(AuthnProvider::getSignedInUser(),$pl->__GET("id"));
            $html = <<<END
                    <b>Playlist créé en session</b><br>";
                    <a href="?action=add-track">Ajouter une piste à la playlist</a>
                    END;
        }
        return $html;
    }
}