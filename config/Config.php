<?php

namespace Config;

use Dotenv\Dotenv;

class Config
{
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    public static function get(string $key)
    {
        return $_ENV[$key];
    }
}
