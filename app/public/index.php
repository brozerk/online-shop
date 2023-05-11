<?php

require '../Autoloader.php';

Autoloader::register(dirname(__DIR__));

use App\App;
use App\Controller\MainController;
use App\Controller\UserController;

$app = new App();

$app->get('/signup', [UserController::class, 'signUp']);
$app->post('/signup', [UserController::class, 'signUp']);

$app->get('/signin', [UserController::class, 'signIn']);
$app->post('/signin', [UserController::class, 'signIn']);

$app->get('/main', [MainController::class, 'goToMain']);

$app->run();