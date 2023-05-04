<?php

$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/signup') {
    require_once './handlers/signup.php';
} elseif ($requestUri === '/signin') {
    require_once './handlers/signin.php';
} elseif ($requestUri === '/main') {
    require_once  './handlers/main.php';
} else {
    require_once './forms/notFound.phtml';
}