<?php

namespace App\Controller;

use App\Repository\CategoryRepository;

class CategoryController
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function goToCatalog(): ?array
    {
        session_start();

        if (isset($_SESSION['id'])) {
            $categories = $this->categoryRepository->getAllCategories();

            return [
                '../Views/categories.phtml',
                [
                    'categories' => $categories
                ],
                true
            ];
        }

        header('Location: /signin');

        return null;
    }
}