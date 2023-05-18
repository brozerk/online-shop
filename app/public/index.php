<?php

require '../Autoloader.php';

Autoloader::register(dirname(__DIR__));

use App\App;
use App\Controller\CategoryController;
use App\Controller\GoodController;
use App\Controller\UserController;
use App\Container;

$settings = require_once '../Config/settings.php';
$dependencies = require_once '../Config/dependencies.php';

$data = array_merge($settings, $dependencies);

$container = new Container($data);

$app = new App($container);

$app->get('/signup', [UserController::class, 'signUp']);
$app->post('/signup', [UserController::class, 'signUp']);

$app->get('/signin', [UserController::class, 'signIn']);
$app->post('/signin', [UserController::class, 'signIn']);

$app->get('/catalog', [CategoryController::class, 'goToCatalog']);
$app->post('/catalog', [CategoryController::class, 'goToCatalog']);

$app->get('/catalog/(?<categoryId>[0-9]+)', [GoodController::class, 'goToCategory']);

$app->run();