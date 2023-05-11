<?php

namespace App\Controller;

class NotFoundController
{
    public function goToNotFound(): array
    {
        $errors = [];

        return [
            './views/notFound.phtml',
            [
                'errors' => $errors
            ],
            false
        ];
    }
}