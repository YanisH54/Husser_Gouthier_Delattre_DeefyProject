<?php

session_start();
use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\repository\DeefyRepository;

require_once "vendor/autoload.php";

DeefyRepository::setConfig(__DIR__ . '/config/db.ini');

$dispatcher = new Dispatcher();
$dispatcher->run();
