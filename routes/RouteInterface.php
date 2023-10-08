<?php

namespace Routes;

interface RouteInterface
{
    public static function get(string $path, string $callback);
    public static function post(string $path, string $callback);
    public static function delete(string $path, string $callback);
    public static function put(string $path, string $callback);
}
