<?php

namespace App\Controller;

class NotFoundController
{
    public function goToNotFound(): array
    {
        $errors = [];

        return [
            '../Views/notFound.phtml',
            [
                'errors' => $errors
            ],
            false
        ];
    }
}