<?php

namespace App\Repository;

use App\Entity\CartGood;
use App\Entity\Good;
use PDO;

class CartGoodRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function getAllByUserId(int $userId): array
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

    public function addByUserIdAndGoodId(int $userId, int $goodId): void
    {
        $cartGood = $this->getByUserIdAndGoodId($userId, $goodId);

        if (!empty($cartGood)) {
            $stmt = $this->connection->prepare('
                UPDATE cart_goods
                SET quantity = :quantity
                WHERE user_id = :userId AND good_id = :goodId
            ');
            $stmt->execute([
                'userId' => $userId,
                'goodId' => $goodId,
                'quantity' => $cartGood->getQuantity() + 1
            ]);
        } else {
            $stmt = $this->connection->prepare('
            INSERT INTO cart_goods
            VALUES (:userId, :goodId, 1)
            ');
            $stmt->execute([
                'userId' => $userId,
                'goodId' => $goodId
            ]);
        }
    }

    public function getByUserIdAndGoodId(int $userId, int $goodId): ?CartGood
    {
        $stmt = $this->connection->prepare('
            SELECT *
            FROM cart_goods
            WHERE user_id = :userId AND good_id = :goodId
        ');
        $stmt->execute([
            'userId' => $userId,
            'goodId' => $goodId
        ]);

        $response = $stmt->fetch();

        if (!empty($response)) {
            return new CartGood($response['user_id'], $response['good_id'], $response['quantity']);
        }
        return null;
    }
}