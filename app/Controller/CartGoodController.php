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
            $cartGoods = $this->cartGoodRepository->getAllByUserId($_SESSION['id']);

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

    public function addToCart()
    {
        session_start();

        if (isset($_SESSION['id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->cartGoodRepository->addByUserIdAndGoodId($_SESSION['id'], $_POST['goodId']);

            $cartGoods = $this->cartGoodRepository->getAllByUserId($_SESSION['id']);

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