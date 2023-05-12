<?php

namespace App\Repository;

use PDO;
use App\Entity\User;

class UserRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function create(User $user): void
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO users (last_name, first_name, middle_name, email, phone_number, password)
                    VALUES (:lastName, :firstName, :middleName, :email, :phoneNumber, :password)'
        );
        $stmt->execute([
            'lastName' => $user->getLastName(),
            'firstName' => $user->getFirstName(),
            'middleName' => $user->getMiddleName(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT)
        ]);
    }

    public function getUserByEmail($email): object
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email=?');
        $stmt->execute([$email]);

        $arr = $stmt->fetch();

        $user = new User($arr['last_name'], $arr['first_name'], $arr['middle_name'], $arr['email'], $arr['phone_number'], $arr['password']);
        $user->setId($arr['id']);

        return $user;
    }
}