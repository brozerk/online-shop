<?php

namespace App;

use App\Exceptions\ClassNotFoundException;

class Container
{
    private array $services;

    public function __construct(array $data)
    {
        $this->services = $data;
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

        if (is_callable($this->services[$key])) {
            $callback = $this->services[$key];

            return $callback($this);
        }
        return $this->services[$key];
    }
}