<?php

namespace App;

use PDO;

class App
{
    private array $routes = [];

    public function run(): void
    {
        $handler = $this->route();

        if (is_array($handler)) {
            list($obj, $method) = $handler;

            if (!is_object($obj)) {
                $obj = new $obj();

                if ($obj instanceof ConnectionAwareInterface) {
                    $obj->setConnection(new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd'));
                }
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
    }

    private function route(): array|callable|null
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