<?php

namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;

class AddUserAction extends Action
{
    public function GET(): string
    {
        return <<<END
            <form method='post' action=?action='add-user'>
            Email : <input type='text' name='email'><br>
            Mot de passe : <input type='text' name='password'><br>
            <button type='submit'>Valider</button></form>
            END;
    }

    public function POST(): string
    {
        $html = null;
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            AuthnProvider::register($email, $password);
            $html = "<b>Utilisateur inscrit</b>";
        } catch (AuthException $e){

            $html ="<b>" .$e->getMessage() . "</b>";
            $html .= <<<END
                 <form method='post' action=?action='add-user'>
                 <input type='text' name='email' value='Email'>
                 <input type='text' name='password' value='Mot de passe'>
                 <button type='submit'></button></form>
                 END;
        }
        return $html;

    }
}