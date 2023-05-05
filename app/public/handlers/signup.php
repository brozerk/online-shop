<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

    $errors = validate($_POST, $connection);

    if (empty($errors)) {
        $lastName = $_POST["last_name"];
        $firstName = $_POST["first_name"];
        $middleName = $_POST["middle_name"] ?? null;
        $email = $_POST["email"];
        $phoneNumber = $_POST["phone_number"];
        $password = $_POST["password"];

        $stmt = $connection->prepare(
            'INSERT INTO users (last_name, first_name, middle_name, email, phone_number, password)
                    VALUES (:lastName, :firstName, :middleName, :email, :phoneNumber, :password)'
        );
        $stmt->execute([
            'lastName' => $lastName,
            'firstName' => $firstName,
            'middleName' => $middleName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}

function validate(array $data, PDO $connection): array
{
    $errors = [];

    $lastNameError = validateLastName($data);
    if (!empty($lastNameError)) {
        $errors['lastName'] = $lastNameError;
    }

    $firstNameError = validateFirstName($data);
    if (!empty($firstNameError)) {
        $errors['firstName'] = $firstNameError;
    }

    $middleNameError = validateMiddleName($data);
    if (!empty($middleNameError)) {
        $errors['middleName'] = $middleNameError;
    }

    $emailError = validateEmail($data, $connection);
    if (!empty($emailError)) {
        $errors['email'] = $emailError;
    }

    $phoneNumberError = validatePhoneNumber($data);
    if (!empty($phoneNumberError)) {
        $errors['phoneNumber'] = $phoneNumberError;
    }

    $passwordError = validatePassword($data);
    if (!empty($passwordError)) {
        $errors['password'] = $passwordError;
    }

    return $errors;
}

function validateLastName(array $data): ?string
{
    $lastName = $data['last_name'] ?? null;

    if (empty($lastName)) {
        return 'Введите фамилию';
    }

    if (strlen($lastName) < 2) {
        return 'Фамилия должна содержать не менее 2 букв';
    }

    return null;
}

function validateFirstName(array $data): ?string
{
    $firstName = $data['first_name'] ?? null;

    if (empty($firstName)) {
        return 'Введите имя';
    }

    if (strlen($firstName) < 2) {
        return 'Имя должно содержать не менее 2 букв';
    }

    return null;
}

function validateMiddleName(array $data): ?string
{
    $middleName = $data['middle_name'] ?? null;

    if (empty($middleName)) {
        return null;
    }

    if (strlen($middleName) < 2) {
        return 'Отчество должно содержать не менее 2 букв';
    }

    return null;
}

function validateEmail(array $data, $connection): ?string
{
    $email = $data['email'] ?? null;

    if (empty($email)) {
        return 'Введите почту';
    }

    if (strlen($email) < 5) {
        return 'Почта должна содержать не менее 5 символов';
    }

    $stmt = $connection->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!empty($user)) {
        return 'Такая почта уже зарегистрирована';
    }

    return null;
}

function validatePhoneNumber(?array $data): ?string
{
    $phoneNumber = $data['phone_number'] ?? null;

    if (empty($phoneNumber)) {
        return 'Введите номер телефона';
    }

    if (strlen($phoneNumber) < 10) {
        return 'Номер телефона должен содержать не менее 10 цифр';
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

require_once './views/signup.phtml';