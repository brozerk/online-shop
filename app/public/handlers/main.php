<?php

session_start();

$errors = [];

if (isset($_SESSION['id'])) {
    return [
        './views/main.phtml',
        [
            'errors' => $errors
        ]
    ];
} else {
    header('Location: /signin');
}