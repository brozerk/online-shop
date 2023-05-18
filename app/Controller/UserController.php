<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

class UserController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function goToSignUp(): array
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = $this->validateSignUp($_POST);

            if (empty($errors)) {
                $lastName = $_POST["last_name"];
                $firstName = $_POST["first_name"];
                $middleName = $_POST["middle_name"] ?? null;
                $email = $_POST["email"];
                $phoneNumber = $_POST["phone_number"];
                $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

                $user = new User($lastName, $firstName, $middleName, $email, $phoneNumber, $password);

                $this->userRepository->create($user);
            }
        }

        return [
            '../Views/signup.phtml',
            [
                'errors' => $errors,
            ],
            true
        ];
    }

    public function goToSignIn(): array
    {
        session_start();

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errors = $this->validateSignIn($_POST);

            if (empty($errors)) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $user = $this->userRepository->getByEmail($email);

                if (is_object($user) && password_verify($password, $user->getPassword())) {
                    $_SESSION['id'] = $user->getId();

                    header("Location: /catalog");
                } else {
                    $errors['verify'] = 'Неправильный логин или пароль';
                }
            }
        }

        return [
            '../Views/signin.phtml',
            [
                'errors' => $errors
            ],
            true
        ];
    }

    private function validateSignUp(array $data): array
    {
        $errors = [];

        $lastNameError = $this->validateLastName($data);
        if (!empty($lastNameError)) {
            $errors['lastName'] = $lastNameError;
        }

        $firstNameError = $this->validateFirstName($data);
        if (!empty($firstNameError)) {
            $errors['firstName'] = $firstNameError;
        }

        $middleNameError = $this->validateMiddleName($data);
        if (!empty($middleNameError)) {
            $errors['middleName'] = $middleNameError;
        }

        $emailError = $this->validateEmailForSignUp($data);
        if (!empty($emailError)) {
            $errors['email'] = $emailError;
        }

        $phoneNumberError = $this->validatePhoneNumber($data);
        if (!empty($phoneNumberError)) {
            $errors['phoneNumber'] = $phoneNumberError;
        }

        $passwordError = $this->validatePassword($data);
        if (!empty($passwordError)) {
            $errors['password'] = $passwordError;
        }

        return $errors;
    }

    private function validateSignIn(array $data): array
    {
        $errors = [];

        $emailError = $this->validateEmail($data);
        if (!empty($emailError)) {
            $errors['email'] = $emailError;
        }

        $passwordError = $this->validatePassword($data);
        if (!empty($passwordError)) {
            $errors['password'] = $passwordError;
        }

        return $errors;
    }

    private function validateLastName(array $data): ?string
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

    private function validateFirstName(array $data): ?string
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

    private function validateMiddleName(array $data): ?string
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

    private function validateEmailForSignUp(array $data): ?string
    {
        $email = $data['email'] ?? null;

        $errors = $this->validateEmail($data);

        if (isset($errors)) {
            return $errors;
        }

        $user = $this->userRepository->getUserByEmail($email);

        if (!empty($user)) {
            return 'Такая почта уже зарегистрирована';
        }

        return null;
    }

    private function validateEmail(array $data): ?string {
        $email = $data['email'] ?? null;

        if (empty($email)) {
            return 'Введите почту';
        }

        if (strlen($email) < 5) {
            return 'Почта должна содержать не менее 5 символов';
        }

        return null;
    }

    private function validatePhoneNumber(?array $data): ?string
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

    private function validatePassword(array $data): ?string
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
}