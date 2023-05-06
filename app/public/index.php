<?php

$requestUri = $_SERVER['REQUEST_URI'];

$handler = route($requestUri);

if ($handler !== './views/notFound.phtml') {
    list($view, $params) = require_once $handler;

    extract($params);

    ob_start();

    require_once $view;

    $content = ob_get_clean();

    $layout = file_get_contents('./views/layout.phtml');

    $result = str_replace('{content}', $content, $layout);

    print_r($result);
} else {
    require_once './views/notFound.phtml';
}

function route(string $uri): string
{
    if (preg_match('#/(?<route>[a-z0-9-_]+)#', $uri, $params)
        &&
        file_exists("./handlers/{$params['route']}.php"))
    {
        return "./handlers/{$params['route']}.php";
    }
    return './views/notFound.phtml';
}