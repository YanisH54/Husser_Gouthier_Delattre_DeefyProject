<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;

class Authz{

    public static function checkRole(int $n) : bool {

        $pdo = DeefyRepository::getInstance();
        $user = unserialize($_SESSION['user']);
        if (!$user){
            throw new AuthException("Erreur : Aucun user connecté");
        }
        $role = $pdo->getUserInfo($user)[1];
        return ($n === $role);
    }

    public static function checkPlaylistCurrOwner(int $idPlaylist) : bool{
        if (Authz::checkRole(100))
            return true;
        $pdo = DeefyRepository::getInstance();
        return $pdo->checkPlaylistOwner(unserialize($_SESSION['user']), $idPlaylist);
    }
}