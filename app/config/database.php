<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/logger.php';

/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
*/
function open_connection(): mysqli
{
    $timezone = env('APP_TIMEZONE', 'Asia/Manila');
    date_default_timezone_set($timezone);

    $hostname = env('DB_HOST', 'localhost');
    $username = env('DB_USERNAME', 'root');
    $password = env('DB_PASSWORD', '');
    $database = env('DB_DATABASE', 'oarsmc_db');
    $port = (int)env('DB_PORT', 3306);

    static $conn = null;
    if ($conn === null) {
        try {
            $conn = new mysqli($hostname, $username, $password, $database, $port);

            if ($conn->connect_error) {
                log_error("Connection failed: {$conn->connect_error}");
                throw new Exception("Database connection failed", 500);
            }

            $conn->set_charset('utf8mb4');

            $offset = '+08:00';
            $conn->query("SET time_zone = '$offset'");
        } catch (Exception $e) {
            log_error("Connection error: {$e->getMessage()}");
            throw new Exception("Database connection failed", 500);
        }
    }

    return $conn;
}
