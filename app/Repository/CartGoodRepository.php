<?php

namespace App\Repository;

use App\Entity\Good;
use PDO;

class CartGoodRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function getByUserId(int $userId): array
    {
        $cartGoods = [];

        $stmt = $this->connection->prepare('
            SELECT *, g.image, g.name, g.color, g.size, g.price
            FROM cart_goods AS c_g
            JOIN goods AS g ON g.id = c_g.good_id
            WHERE c_g.user_id = ?
        ');
        $stmt->execute([$userId]);

        $response = $stmt->fetchAll();

        foreach ($response as $value) {
            $good = new Good(
                $value['name'],
                $value['category_id'],
                $value['color'],
                $value['size'],
                $value['price'],
                $value['image']
            );

            $good->setId($value['id']);

            $quantity = $value['quantity'];

            $cartGood = ['good' => $good, 'quantity' => $quantity];

            $cartGoods[] = $cartGood;
        }

        return $cartGoods;
    }
}