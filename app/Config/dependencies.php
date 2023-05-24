<?php

use App\Container;
use App\Controller\CartGoodController;
use App\Controller\CategoryController;
use App\Controller\GoodController;
use App\Controller\NotFoundController;
use App\Controller\UserController;
use App\FileLogger;
use App\LoggerInterface;
use App\Repository\CartGoodRepository;
use App\Repository\CategoryRepository;
use App\Repository\GoodRepository;
use App\Repository\UserRepository;
use App\Services\AuthenticationService;
use App\ViewRenderer;

return [
    'db' => function(Container $container) {
        $settings = $container->get('settings');
        $host = $settings['db']['host'];
        $dbname = $settings['db']['dbname'];
        $username = $settings['db']['username'];
        $password = $settings['db']['password'];

        return new PDO("pgsql:host=$host;dbname=$dbname", "$username", "$password");
    },

    NotFoundController::class => function (Container $container) {
        $renderer = $container->get(ViewRenderer::class);

        return new NotFoundController($renderer);
    },

    UserRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new UserRepository($connection);
    },

    UserController::class => function (Container $container) {
        $userRepository = $container->get(UserRepository::class);
        $authenticationService = $container->get(AuthenticationService::class);
        $renderer = $container->get(ViewRenderer::class);

        return new UserController($userRepository, $authenticationService, $renderer);
    },

    GoodRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new GoodRepository($connection);
    },

    GoodController::class => function (Container $container) {
        $goodRepository = $container->get(GoodRepository::class);
        $cartGoodRepository = $container->get(CartGoodRepository::class);
        $renderer = $container->get(ViewRenderer::class);

        return new GoodController($goodRepository, $cartGoodRepository, $renderer);
    },

    CategoryRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CategoryRepository($connection);
    },

    CategoryController::class => function (Container $container) {
        $categoryRepository = $container->get(CategoryRepository::class);
        $cartGoodRepository = $container->get(CartGoodRepository::class);
        $renderer = $container->get(ViewRenderer::class);

        return new CategoryController($categoryRepository, $cartGoodRepository, $renderer);
    },

    CartGoodRepository::class => function (Container $container) {
        $connection = $container->get('db');


        return new CartGoodRepository($connection);
    },

    CartGoodController::class => function (Container $container) {
        $cartGoodRepository = $container->get(CartGoodRepository::class);
        $renderer = $container->get(ViewRenderer::class);

        return new CartGoodController($cartGoodRepository, $renderer);
    },

    LoggerInterface::class => function () {
        return new FileLogger();
    }
];