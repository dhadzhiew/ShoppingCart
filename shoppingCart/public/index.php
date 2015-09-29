<?php
error_reporting(E_ALL ^ E_NOTICE);

include '../../MVC/App.php';
$app = \DH\Mvc\App::getInstance();

$app->setRouter('JsonRPCRouter');

$app->run();