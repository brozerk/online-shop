<?php

namespace App\Repository;

use App\Entity\Category;
use PDO;

class CategoryRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function getAll(): array
    {
        $categories = [];

        $stmt = $this->connection->query('
            SELECT *
            FROM categories
        ');

        $response = $stmt->fetchAll();

        foreach ($response as $value) {
            $category = new Category(
                $value['name'],
                $value['image']
            );

            $category->setId($value['id']);

            $categories[] = $category;
        }

        return $categories;
    }
}