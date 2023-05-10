<?php

class Autoloader
{
    public static function register(string $appRoot): void
    {
        spl_autoload_register(function ($class) use($appRoot) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

            $file = preg_replace('#^App#', $appRoot, $file);

            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}