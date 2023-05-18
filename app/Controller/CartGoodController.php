<?php

namespace App\Controller;

use App\Repository\CartGoodRepository;

class CartGoodController
{
    public function __construct(private CartGoodRepository $cartGoodRepository)
    {
    }

    public function goToCart(): ?array
    {
        session_start();

        if (isset($_SESSION['id'])) {
            $cartGoods = $this->cartGoodRepository->getByUserId($_SESSION['id']);

            return [
                '../Views/cart.phtml',
                [
                    'cartGoods' => $cartGoods
                ],
                true
            ];
        }

        header('Location: /signin');

        return null;
    }
}