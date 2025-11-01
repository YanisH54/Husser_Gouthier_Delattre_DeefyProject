<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;

abstract class ActionConnecte extends Action
{
    public function execute() : string {
        try {
            // Si l'utiilisateur est connecté, aucune erreur n'est levée
            AuthnProvider::getSignedInUser();
            return parent::execute();
        } catch (AuthException $e) {
            return $e->getMessage();
        }
    }
}