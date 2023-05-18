<?php

namespace App\Entity;

class CartGood
{
    private int $userId;
    private int $goodId;
    private int $quantity;

    public function __construct(int $userId, int $goodId, int $quantity)
    {
        $this->setUserId($userId);
        $this->setGoodId($goodId);
        $this->setQuantity($quantity);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getGoodId(): int
    {
        return $this->goodId;
    }

    /**
     * @param int $goodId
     */
    public function setGoodId(int $goodId): void
    {
        $this->goodId = $goodId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}