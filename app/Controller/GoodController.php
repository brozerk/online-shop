<?php

namespace App\Controller;

use App\Repository\CartGoodRepository;
use App\Repository\GoodRepository;
use App\ViewRenderer;

class GoodController
{
    public function __construct(private GoodRepository $goodRepository, private CartGoodRepository $cartGoodRepository, private ViewRenderer $renderer)
    {
    }

    public function goToCategory(int $categoryId): ?string
    {
        session_start();

        if (isset($_SESSION['id'])) {
            $goods = $this->goodRepository->getAllByCategoryId($categoryId);
            $cartGoodsQuantity = $this->cartGoodRepository->getQuantityByUserId($_SESSION['id']);

            if (!empty($goods)) {
                return $this->renderer->render(
                    '../Views/goods.phtml',
                    [
                        'goods' => $goods,
                        'cartGoodsQuantity' => $cartGoodsQuantity
                    ],
                    true
                );
            }
            return $this->renderer->render(
                '../Views/notFound.phtml',
                [
                ],
                false
            );
        }

        header('Location: /signin');

        return null;
    }
}