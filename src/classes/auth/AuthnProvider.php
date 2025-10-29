<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider
{
    public static function signin(string $email,
                                  string $passwd2check): void {
        $bdd = DeefyRepository::getInstance();
        $hash = $bdd->getUserInfo($email)[0];
        if (!$hash ||!password_verify($passwd2check, $hash))
            throw new AuthException("Auth error : invalid credentials");
        $_SESSION['user'] = serialize($email);

    }
    public static function register( string $email,
                                     string $pass): void {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new AuthException("error : Email incorrect");
        if (strlen($pass) < 10)
            throw new AuthException("Erreur : Mot de passe trop court");
        $bdd = DeefyRepository::getInstance();
        if ($bdd->verifieEmailExiste($email)){
            throw new AuthException("Erreur : Email déja enregistré");
        }
        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost'=>12]);
        $statm = "insert into User (email, passwd, role) values($email, $hash, 1)";

    }

    public static function getSignedInUser( ): string {
        if (!isset($_SESSION['user']))
            throw new AuthException("Auth error : not signed in");
        return unserialize($_SESSION['user'] ) ;
    }

}