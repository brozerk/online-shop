<?php

namespace App;

class FileLogger implements LoggerInterface
{
    public function writeError(string $message, array $data): void
    {
        $f = fopen("../Logs/errors.php", "a");
        fwrite($f, "{$message}\n");
        $data = json_encode($data);
        fwrite($f, "{$data}\n\n");
        fclose($f);
    }

    public function writeDebug(string $message, array $data): void
    {
        $f = fopen("../Logs/debugs.php", "a");
        fwrite($f, "{$message}\n");
        $data = json_encode($data);
        fwrite($f, "{$data}\n\n");
        fclose($f);
    }

    public function writeWarning(string $message, array $data): void
    {
        $f = fopen("../Logs/warnings.php", "a");
        fwrite($f, "{$message}\n");
        $data = json_encode($data);
        fwrite($f, "{$data}\n\n");
        fclose($f);
    }
}