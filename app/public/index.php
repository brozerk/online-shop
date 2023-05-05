<?php

$requestUri = $_SERVER['REQUEST_URI'];

require_once route($requestUri);

function route(?string $requestUri): string
{
    if (preg_match('#/(?<route>[a-z0-9-_]+)#', $requestUri, $params)
        &&
        file_exists("./handlers/{$params['route']}.php"))
    {
        return "./handlers/{$params['route']}.php";
    }
    return './views/notFound.phtml';
}