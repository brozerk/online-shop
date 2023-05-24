<?php

namespace App\Repository;

use App\Entity\CartGood;
use App\Entity\Good;
use App\Entity\User;
use PDO;

class CartGoodRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function getAllByUserId(int $userId): array
    {
        $stmt = $this->connection->prepare('
            SELECT *
            FROM cart_goods AS c_g
            JOIN users AS u ON u.id = c_g.user_id
            JOIN goods AS g ON g.id = c_g.good_id
            WHERE c_g.user_id = ?
        ');
        $stmt->execute([$userId]);
        $response = $stmt->fetchAll();

        $cartGoods = [];

        foreach ($response as $value) {
            $user = new User(
                $value['last_name'],
                $value['first_name'],
                $value['middle_name'],
                $value['email'],
                $value['phone_number'],
                $value['password']
            );
            $user->setId($value['id']);

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

            $cartGood = new CartGood($user, $good, $quantity);

            $cartGoods[] = $cartGood;
        }

        return $cartGoods;
    }

    public function addByUserIdAndGoodId(int $userId, int $goodId): void
    {
            $stmt = $this->connection->prepare('
            INSERT INTO cart_goods AS c_g
            VALUES (:userId, :goodId, :quantity)
            ON CONFLICT (user_id, good_id) DO UPDATE
            SET quantity = c_g.quantity + 1
            ');
            $stmt->execute([
                'userId' => $userId,
                'goodId' => $goodId,
                'quantity' => 1
            ]);
    }

    public function getQuantityByUserId(int $userId): int
    {
        $stmt = $this->connection->prepare('
            SELECT SUM(quantity)
            FROM cart_goods
            WHERE user_id = ?
            GROUP BY user_id
        ');
        $stmt->execute([$userId]);
        $response = $stmt->fetch();

        if ($response) {
            return $response['sum'];
        }
        return 0;
    }
}