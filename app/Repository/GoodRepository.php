<?php

namespace App\Repository;

use App\Entity\Good;
use PDO;

class GoodRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function getAllGoods(int $categoryId): array
    {
        $goods = [];

        $stmt = $this->connection->prepare('
            SELECT *
            FROM goods
            WHERE category_id=?
        ');
        $stmt->execute([$categoryId]);

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

            $goods[] = $good;
        }

        return $goods;
    }
}