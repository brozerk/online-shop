<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;

class AuthenticationService
{
    private User $user;

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function verify(string $email, string $password): bool
    {
        $user = $this->userRepository->getByEmail($email);

        if (password_verify($password, $user->getPassword())) {

            return true;
        }
        return false;
    }
}