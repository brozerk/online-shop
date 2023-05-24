<?php

namespace App\Controller;

use App\ViewRenderer;

class NotFoundController
{
    public function __construct(private ViewRenderer $renderer)
    {
    }

    public function goToNotFound(): ?string
    {
        $errors = [];

        return $this->renderer->render(
            '../Views/notFound.phtml',
            [
                'errors' => $errors
            ],
            false
        );
    }
}