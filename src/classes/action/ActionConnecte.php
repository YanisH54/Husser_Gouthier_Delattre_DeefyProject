<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;

abstract class ActionConnecte extends Action
{
    public function execute() : string {
        try {
            AuthnProvider::getSignedInUser();
            return parent::execute();
        } catch (AuthException $e) {
            return $e->getMessage();
        }
    }
}