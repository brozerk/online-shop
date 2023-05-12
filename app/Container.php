<?php

namespace App;

use App\Exceptions\ClassNotFoundException;

class Container
{
    private array $services = [];

    public function set(string $key, callable $callback): void
    {
        $this->services[$key] = $callback;
    }

    /**
     * @throws ClassNotFoundException
     */
    public function get(string $key)
    {
        if (!isset($this->services[$key])) {
            if (class_exists($key)) {
                return new $key();
            }
            throw new ClassNotFoundException();
        }
        $callback = $this->services[$key];

        return $callback($this);
    }
}