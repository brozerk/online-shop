<?php

namespace App\Controller;

use App\Repository\CartGoodRepository;
use App\ViewRenderer;

class CartGoodController
{
    public function __construct(private CartGoodRepository $cartGoodRepository, private ViewRenderer $renderer)
    {
    }

    public function goToCart(): ?string
    {
        session_start();

        if (isset($_SESSION['id'])) {
            $cartGoods = $this->cartGoodRepository->getAllByUserId($_SESSION['id']);
            $cartGoodsQuantity = $this->cartGoodRepository->getQuantityByUserId($_SESSION['id']);

            return $this->renderer->render(
                '../Views/cart.phtml',
                [
                    'cartGoods' => $cartGoods,
                    'cartGoodsQuantity' => $cartGoodsQuantity
                ],
                true
            );
        }

        header('Location: /signin');

        return null;
    }

    public function addToCart(): int
    {
        session_start();

        if (isset($_SESSION['id'])) {
            $this->cartGoodRepository->addByUserIdAndGoodId($_SESSION['id'], $_POST['goodId']);

//            header('Location: /cart');

            return $this->cartGoodRepository->getQuantityByUserId($_SESSION['id']);
        }
    }
}