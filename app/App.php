<?php

namespace App;

use App\Exceptions\ClassNotFoundException;

class App
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    public function run(): void
    {
        try {
            $handler = $this->route();

            if (is_array($handler)) {
                list($obj, $method) = $handler;

                if (!is_object($obj)) {
                    $obj = $this->container->get($obj);
                }

                $response = $obj->$method();
            } else {
                $response = call_user_func($handler);
            }

            list($view, $params, $isLayout) = $response;

            extract($params);

            if ($isLayout) {
                ob_start();

                require_once $view;

                $content = ob_get_clean();

                $layout = file_get_contents('./views/layout.phtml');

                $result = str_replace('{content}', $content, $layout);

                print_r($result);
            } else {
                require_once $view;
            }
        } catch (ClassNotFoundException $exception) {
            $logger = $this->container->get(LoggerInterface::class);

            $data = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ];

            $logger->writeError('Произошла ошибка во время обработки запроса', $data);

            require '../public/views/error500.phtml';
        }
    }

    private function route(): array|callable
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $pattern => $handler) {
            if (preg_match("#^$pattern$#", $uri)) {
                return $handler;
            }
        }
        return ['App\Controller\NotFoundController', 'goToNotFound'];
    }

    public function get(string $route, array|callable $handler): void
    {
        $this->routes['GET'][$route] = $handler;
    }

    public function post(string $route, array|callable $handler): void
    {
        $this->routes['POST'][$route] = $handler;
    }
}