<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

$is_prod = env('APP_ENV') === 'production';

if ($is_prod) {
    # Production environment: disable error display and log errors to a file
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../../storage/logs/production-logs.txt');
} else {
    # Development environment: display errors and log them to a file
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../../storage/logs/local-logs.txt');
}

error_reporting(E_ALL & ~E_DEPRECATED);

function log_request(string $action, array $data): void
{
    $timestamp = date('[d-M-Y H:i:s e]');
    $log_message = sprintf(
        "[%s] Action: %s, Data: %s\n",
        $timestamp,
        $action,
        json_encode($data, JSON_PRETTY_PRINT)
    );

    error_log($log_message);
}

function log_error(string $message, mixed $context = null): void
{
    $timestamp = date('[d-M-Y H:i:s e]');
    $log_message = sprintf(
        "[%s] ERROR: %s%s\n",
        $timestamp,
        $message,
        $context ? ", Context: " . json_encode($context, JSON_PRETTY_PRINT) : ''
    );

    error_log($log_message);
}
