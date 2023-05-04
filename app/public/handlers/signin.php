<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = validateSignIn($_POST);

    if (empty($errors)) {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

        $stmt = $connection->prepare('SELECT * FROM users WHERE email=?');
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];

            header("Location: /main");
        }
    }
}

function validateSignIn(array $data): array
{
    $errors = [];

    $emailError = validateEmail($data);
    if (!empty($emailError)) {
        $errors['email'] = $emailError;
    }

    $passwordError = validatePassword($data);
    if (!empty($passwordError)) {
        $errors['password'] = $passwordError;
    }

    return $errors;
}

function validateEmail(array $data): ?string {
    $email = $data['email'] ?? null;

    if (empty($email)) {
        return 'Введите почту';
    }

    if (strlen($email) < 5) {
        return 'Почта должна содержать не менее 5 символов';
    }

    return null;
}

function validatePassword(array $data): ?string
{
    $password = $data['password'] ?? null;

    if (empty($password)) {
        return 'Введите пароль';
    }

    if (strlen($password) < 8) {
        return 'Пароль должен содержать не менее 8 символов';
    }

    return null;
}

require_once './forms/signin.phtml';