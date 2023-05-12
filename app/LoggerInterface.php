<?php

namespace App;

interface LoggerInterface
{
    public function writeError(string $message, array $data);

    public function writeDebug(string $message, array $data);

    public function writeWarning(string $message, array $data);
}