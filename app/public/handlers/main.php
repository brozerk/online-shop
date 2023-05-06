<?php

session_start();

$errors = [];

if (isset($_SESSION['id'])) {
    return [
        './views/main.phtml',
        [
            'errors' => $errors
        ],
        true
    ];
} else {
    header('Location: /signin');
}