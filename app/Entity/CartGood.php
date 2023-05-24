<?php

namespace App\Entity;

class CartGood
{
    private User $user;
    private Good $good;
    private int $quantity;

    public function __construct(User $user, Good $good, int $quantity)
    {
        $this->setUser($user);
        $this->setGood($good);
        $this->setQuantity($quantity);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Good
     */
    public function getGood(): Good
    {
        return $this->good;
    }

    /**
     * @param Good $good
     */
    public function setGood(Good $good): void
    {
        $this->good = $good;
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