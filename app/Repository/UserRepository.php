<?php

namespace App\Repository;

use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
    }

    public function create($data): void
    {
        $lastName = $data["last_name"];
        $firstName = $data["first_name"];
        $middleName = $data["middle_name"] ?? null;
        $email = $data["email"];
        $phoneNumber = $data["phone_number"];
        $password = $data["password"];

        $stmt = $this->connection->prepare(
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

    public function authenticate($data): void
    {
        $email = $data['email'];
        $password = $data['password'];

        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email=?');
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];

            header("Location: /main");
        } else {
            $errors['verify'] = 'Неправильный логин или пароль';
        }
    }
}