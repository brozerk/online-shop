<?php

namespace App\Repository;

use App\Entity\Good;
use PDO;

class GoodRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function getAllByCategoryId(int $categoryId): array
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

    public function getByGoodId(int $goodId): ?object
    {
        $stmt = $this->connection->prepare('
            SELECT *
            FROM goods
            WHERE id = ?
        ');
        $stmt->execute([$goodId]);
        $response = $stmt->fetch();

        if (!empty($response)) {
            $good = new Good(
                $response['name'],
                $response['category_id'],
                $response['color'],
                $response['size'],
                $response['price'],
                $response['image']
            );
            $good->setId($response['id']);

            return $good;
        }
        return null;
    }
}