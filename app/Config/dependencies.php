<?php

use App\Container;
use App\Controller\UserController;
use App\FileLogger;
use App\LoggerInterface;
use App\Repository\UserRepository;

return [
    'db' => function(Container $container) {
        $settings = $container->get('settings');
        $host = $settings['db']['host'];
        $dbname = $settings['db']['dbname'];
        $username = $settings['db']['username'];
        $password = $settings['db']['password'];

        return new PDO("pgsql:host=$host;dbname=$dbname", "$username", "$password");
    },
    UserRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new UserRepository($connection);
    },
    UserController::class => function (Container $container) {
        $userRepository = $container->get(UserRepository::class);

        return new UserController($userRepository);
    },
    LoggerInterface::class => function () {
        return new FileLogger();
    }
];