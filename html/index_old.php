<?php
require '../vendor/autoload.php';
require '../base/config.php';

ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL | E_NOTICE);

$route = new \Base\Route();
$route->add('/', \App\Controller\Login::class);

$app = new \Base\Application($route);
$app->run();