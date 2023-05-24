<?php

namespace App\Controller;

use App\Repository\CartGoodRepository;
use App\Repository\CategoryRepository;
use App\ViewRenderer;

class CategoryController
{
    public function __construct(private CategoryRepository $categoryRepository, private CartGoodRepository $cartGoodRepository, private ViewRenderer $renderer)
    {
    }

    public function goToCatalog(): ?string
    {
        session_start();

        if (isset($_SESSION['id'])) {
            $categories = $this->categoryRepository->getAll();
            $cartGoodsQuantity = $this->cartGoodRepository->getQuantityByUserId($_SESSION['id']);

            return $this->renderer->render(
                '../Views/categories.phtml',
                [
                    'categories' => $categories,
                    'cartGoodsQuantity' => $cartGoodsQuantity
                ],
                true
            );
        }

        header('Location: /signin');

        return null;
    }
}