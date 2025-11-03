<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;

class SignInAction extends Action
{

    public function GET(): string
    {
        return <<<END
            <form method='post' action=?action=sign-in>
            Email : <input type='text' name='email'><br>
            Mot de passe : <input type='text' name='password'><br>
            <button type='submit'>Valider</button></form>
        END;
    }

    public function POST(): string
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            AuthnProvider::signin($email, $password);
            $html = "<b>Utilisateur connecté</b>";
        } catch (AuthException $e){
            $html = "<b>" .$e->getMessage() . "</b>";
            $html .= <<<END
                <b>Connexion</b>
                <form method='post' action=?action=sign-in>
                Email : <input type='text' name='email'><br>
                Mot de passe : <input type='text' name='password'><br>
                <button type='submit'>Valider</button></form>
                END;
        }
        return $html;
    }
}