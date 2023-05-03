<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

    $errors = validate($_POST, $connection);

    if (empty($errors)) {
        $lastName = $_POST["last_name"] ?? null;
        $firstName = $_POST["first_name"] ?? null;
        $middleName = $_POST["middle_name"] ?? null;
        $email = $_POST["email"] ?? null;
        $phoneNumber = $_POST["phone_number"] ?? null;
        $password = $_POST["password"] ?? null;
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sth = $connection->prepare(
            'INSERT INTO users (last_name, first_name, middle_name, email, phone_number, password)
                    VALUES (:lastName, :firstName, :middleName, :email, :phoneNumber, :password)'
        );
        $sth->execute([
            'lastName' => $lastName,
            'firstName' => $firstName,
            'middleName' => $middleName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'password' => $password
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
    $lastName = $data['lastName'] ?? null;

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
    $firstName = $data['firstName'] ?? null;

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
    $middleName = $data['middleName'] ?? null;

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

    $stmt = $connection->prepare("SELECT email FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!empty($user)) {
        return 'Такая почта уже зарегистрирована';
    }

    return null;
}

function validatePhoneNumber(?array $data): ?string
{
    $phoneNumber = $data['phoneNumber'] ?? null;

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
?>

<form action="" method="POST">
    <div class="container">
        <h1>Регистрация</h1>

        <p>Введите данные для создания аккаунта</p>

        <hr>
        <label for="last name"><b>Фамилия</b>
            <span style="color: red">
                <?= $errors['lastName'] ?? ''; ?>
            </span>
        </label>
        <input type="text" placeholder="Введите фамилию" name="last name" required>


        <label for="first name"><b>Имя</b>
            <span style="color: red">
                <?= $errors['firstName'] ?? ''; ?>
            </span>
        </label>
        <input type="text" placeholder="Введите имя" name="first name" required>

        <label for="middle name"><b>Отчество</b>
            <span style="color: red">
                <?= $errors['middleName'] ?? ''; ?>
            </span>
        </label>
        <input type="text" placeholder="Введите отчество" name="middle name">

        <label for="email"><b>Электронная почта</b>
            <span style="color: red">
                <?= $errors['email'] ?? ''; ?>
            </span>
        </label>
        <input type="text" placeholder="Введите адрес электронной почты" name="email" required>

        <label for="phone number"><b>Телефон</b>
            <span style="color: red">
                <?= $errors['phoneNumber'] ?? ''; ?>
            </span>
        </label>
        <input type="text" placeholder="Введите номер телефона" name="phone number" required>


        <label for="password"><b>Пароль</b>
            <span style="color: red">
                <?= $errors['password'] ?? ''; ?>
            </span>
        </label>
        <input type="password" placeholder="Введите пароль" name="password" required>


        <button type="submit" class="registerbtn">Зарегистрироваться</button>
    </div>
</form>

<style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
    padding: 16px;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
    background-color: #ddd;
        outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
    border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .registerbtn {
    background-color: #4CAF50;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
    opacity:1;
}

    /* Add a blue text color to links */
    a {
    color: dodgerblue;
}

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
    background-color: #f1f1f1;
        text-align: center;
    }
</style>