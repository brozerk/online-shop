<?php

require '../Autoloader.php';

Autoloader::register(dirname(__DIR__));

use App\App;
use App\Controller\UserController;
use App\Controller\MainController;
use App\FileLogger;
use App\LoggerInterface;
use App\Repository\UserRepository;
use App\Container;

$container = new Container();

$container->set(LoggerInterface::class, function () {
    return new FileLogger();
});

$container->set(UserController::class, function (Container $container) {
    $userRepository = $container->get(UserRepository::class);

    return new UserController($userRepository);
});

$container->set(UserRepository::class, function () {
    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

    return new UserRepository($connection);
});

$app = new App($container);

$app->get('/signup', [UserController::class, 'signUp']);
$app->post('/signup', [UserController::class, 'signUp']);

$app->get('/signin', [UserController::class, 'signIn']);
$app->post('/signin', [UserController::class, 'signIn']);

$app->get('/main', [MainController::class, 'goToMain']);

$app->run();