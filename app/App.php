<?php

namespace App;

class App
{
    private array $routes = [];

    public function run(): void
    {
        $requestUri = $_SERVER['REQUEST_URI'];

        $handler = $this->route();

        list($view, $params, $isLayout) = require_once $handler;

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
    }

    private function route(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        foreach ($this->routes as $pattern => $handler) {
            if (preg_match("#^$pattern$#", $uri)) {
                if (file_exists($handler)) {
                    return $handler;
                }
            }
        }
        return './handlers/notFound.php';
    }

    public function addRoute(string $route, string $handlerPath): void
    {
        $this->routes[$route] = $handlerPath;
    }
}