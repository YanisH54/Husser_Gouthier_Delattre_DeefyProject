<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;

class Authz
{

    public static function checkRole(int $n) : bool {

        $pdo = DeefyRepository::getInstance();
        $user = $_SESSION['user'];
        if (!$user){
            throw new AuthException("Erreur : Aucun user connecté");
        }
        $role = $pdo->getUserInfo($user);
        return ($n === $role);
    }

    public static function checkPlaylistCurrOwner(int $idPlaylist) : bool{
        if (Authz::checkRole(100))
            return true;
        $pdo = DeefyRepository::getInstance();
        return $pdo->checkPlaylistOwner($_SESSION['user'], $idPlaylist);
    }
}