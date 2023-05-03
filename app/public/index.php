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
                    VALUES (:last_name, :first_name, :middle_name, :email, :phone_number, :password)'
        );
        $sth->execute([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'password' => $password
        ]);
    }
}

function validate(array $data, PDO $connection): array
{
    $errors = [];

    $lastNameError = validateLastName($data);
    if (empty($lastNameError)) {
        $errors['lastName'] = $lastNameError;
    }

    $errors['firstName'] = validateFirstName($data['first_name']);
    $errors['middleName'] = validateMiddleName($data['middle_name']);
    $errors['email'] = validateEmail($data['email'], $connection);
    $errors['phoneNumber'] = validatePhoneNumber($data['phone_number']);
    $errors['password'] = validatePassword($data['password']);

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

function validateFirstName(?string $firstName): ?string
{
    if (!empty($firstName)) {
        if (strlen($firstName) >= 2) {
            return null;
        } else {
            return 'Имя должно содержать не менее 2 букв';
        }
    } else {
        return 'Введите имя';
    }
}

function validateMiddleName(?string $middleName): ?string
{
    if (!empty($middleName)) {
        if (strlen($middleName) >= 2) {
            return null;
        } else {
            return 'Отчество должно содержать не менее 2 букв';
        }
    } else {
        return null;
    }
}

function validateEmail(?string $email, $connection): ?string
{
    if (!empty($email)) {
        if (strlen($email) >= 5) {
            $stmt = $connection->prepare("SELECT * FROM users WHERE email=?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user === false) {
                return null;
            } else {
                return 'Такая почта уже зарегистрирована';
            }
        } else {
            return 'Почта должна содержать не менее 5 символов';
        }
    } else {
        return 'Введите почту';
    }
}

function validatePhoneNumber(?string $phoneNumber): ?string
{
    if (!empty($phoneNumber)) {
        if (strlen($phoneNumber) >= 10) {
            return null;
        } else {
            return 'Номер телефона должен содержать не менее 10 цифр';
        }
    } else {
        return 'Введите номер телефона';
    }
}

function validatePassword(?string $password): ?string
{
    if (!empty($password)) {
        if (strlen($password) >= 8) {
            return null;
        } else {
            return 'Пароль должен содержать не менее 8 символов';
        }
    } else {
        return 'Введите пароль';
    }
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