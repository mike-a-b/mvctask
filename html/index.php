<?php
require "../vendor/autoload.php";

use App\Controller\User;


$userController = new User();
$userController->indexAction();