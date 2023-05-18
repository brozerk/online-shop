<?php

namespace App\Controller;

use App\Repository\GoodRepository;

class GoodController
{
    public function __construct(private GoodRepository $goodRepository)
    {
    }

    public function goToCategory(int $categoryId): ?array
    {
        session_start();

        if (isset($_SESSION['id'])) {
            $goods = $this->goodRepository->getAll($categoryId);

            if (!empty($goods)) {
                return [
                    '../Views/goods.phtml',
                    [
                        'goods' => $goods
                    ],
                    true
                ];
            }
            return [
                '../Views/notFound.phtml',
                [

                ],
                false
            ];
        }

        header('Location: /signin');

        return null;
    }
}