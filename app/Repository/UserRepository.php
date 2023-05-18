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
        $stmt = $this->connection->prepare('
            INSERT INTO users (last_name, first_name, middle_name, email, phone_number, password)
            VALUES (:lastName, :firstName, :middleName, :email, :phoneNumber, :password)
        ');
        $stmt->execute([
            'lastName' => $user->getLastName(),
            'firstName' => $user->getFirstName(),
            'middleName' => $user->getMiddleName(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT)
        ]);
    }

    public function getByEmail(string $email): object|bool
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email=?');
        $stmt->execute([$email]);

        $response = $stmt->fetch();

        if (!empty($response)) {
            $user = new User($response['last_name'], $response['first_name'], $response['middle_name'], $response['email'], $response['phone_number'], $response['password']);
            $user->setId($response['id']);

            return $user;
        }
        return $response;
    }
}