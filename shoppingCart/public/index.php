<?php
error_reporting(E_ALL ^ E_NOTICE);

require '../../MVC/App.php';
$app = \DH\Mvc\App::getInstance();

$app->run();

$app->getSession()->counter += 1;

echo $app->getSession()->counter;