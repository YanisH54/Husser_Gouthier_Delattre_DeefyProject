<?php

session_start();
use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\repository\DeefyRepository;

require_once "vendor/autoload.php";

DeefyRepository::setConfig("db.conf");

$dispatcher = new Dispatcher();
$dispatcher->run();
