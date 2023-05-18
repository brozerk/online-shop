<?php

use App\Container;
use App\Controller\CartGoodController;
use App\Controller\CategoryController;
use App\Controller\GoodController;
use App\Controller\UserController;
use App\FileLogger;
use App\LoggerInterface;
use App\Repository\CartGoodRepository;
use App\Repository\CategoryRepository;
use App\Repository\GoodRepository;
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

    GoodRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new GoodRepository($connection);
    },

    GoodController::class => function (Container $container) {
        $goodRepository = $container->get(GoodRepository::class);

        return new GoodController($goodRepository);
    },

    CategoryRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CategoryRepository($connection);
    },

    CategoryController::class => function (Container $container) {
        $categoryRepository = $container->get(CategoryRepository::class);

        return new CategoryController($categoryRepository);
    },

    CartGoodRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CartGoodRepository($connection);
    },

    CartGoodController::class => function (Container $container) {
        $cartGoodRepository = $container->get(CartGoodRepository::class);

        return new CartGoodController($cartGoodRepository);
    },

    LoggerInterface::class => function () {
        return new FileLogger();
    }
];