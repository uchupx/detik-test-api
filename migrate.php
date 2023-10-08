<?php
include 'autoload.php';

use Config\Config;

new Config();

// remove first args
unset($argv[0]);
$url = "mysql://" . Config::get("DATABASE_USERNAME") . ":" . Config::get("DATABASE_PASSWORD") . "@" . Config::get("DATABASE_HOST") . ":" . Config::get("DATABASE_PORT") . "/" . Config::get("DATABASE_NAME");
if ($argv[1] == "create") {
    echo shell_exec('php vendor/bin/migrate ' . implode(" ", $argv));
} else {
    echo shell_exec('php vendor/bin/migrate ' . implode(" ", $argv) . ' ' . $url);
}
