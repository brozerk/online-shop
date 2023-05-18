<?php

namespace App;

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

            list($handler, $params) = $handler;

            if (is_array($handler)) {
                list($obj, $method) = $handler;

                if (!is_object($obj)) {
                    $obj = $this->container->get($obj);
                }

                if (empty($params)) {
                    $response = $obj->$method();
                } else {
                    $params = array_values($params);
                    $response = $obj->$method(...$params);
                }
            } else {
                $response = call_user_func($handler);
            }

            list($view, $params, $isLayout) = $response;

            extract($params);

            if ($isLayout) {
                ob_start();

                require_once $view;

                $content = ob_get_clean();

                $layout = file_get_contents('../Views/layout.phtml');

                $result = str_replace('{content}', $content, $layout);

                print_r($result);
            } else {
                require_once $view;
            }
        } catch (\Throwable $exception) {
            $logger = $this->container->get(LoggerInterface::class);

            $data = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ];

            $logger->writeError('Произошла ошибка во время обработки запроса', $data);

            require '../Views/error500.phtml';
        }
    }

    private function route(): array|callable
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $pattern => $handler) {
            if (preg_match("#^$pattern$#", $uri, $params)) {
                foreach ($params as $key => $value) {
                    if ($key === 0 || intval($key)) {
                        unset($params[$key]);
                    }
                }

                return [
                    $handler,
                    $params
                ];
            }
        }

        return [['App\Controller\NotFoundController', 'goToNotFound'], null];
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