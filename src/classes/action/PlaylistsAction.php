<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\repository\DeefyRepository;

class PlaylistsAction extends ActionConnecte
{
    public function GET(): string
    {
        $html = "<p><b>Liste de vos playlists</b></p>";
        $pdo = DeefyRepository::getInstance();
        $playlists = $pdo->getListPlaylists(AuthnProvider::getSignedInUser());
        foreach ($playlists as $playlist){
            $html .= "<p>Nom playlist : " . $playlist->__GET("nom") . "</p>";
            $html .= "<a href='?action=playlists&playlist=" .$playlist->__GET("id"). "'>Selectionner cette playlist</a><br>";
        }
        return $html;
    }

    public function POST(): string
    {
        $idPlaylist = $_GET['playlist'];

        if (filter_var($idPlaylist,FILTER_SANITIZE_NUMBER_INT))
            $html = "<b>Erreur : identifiant playlist invalide</b>";
        else {
            if (Authz::checkPlaylistCurrOwner($idPlaylist))
            $pdo = DeefyRepository::getInstance();
            try {
                $playlist = $pdo->findPlaylistById($idPlaylist);

            } catch (InvalidPropertyValueException $e) {
                $html = "<b>Erreur : playlist introuvable</b>";
            }
        }

        return $this->GET();
    }
}