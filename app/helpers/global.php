<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/logger.php';

function return_response(array $response): void
{
    //if the status is not set, set default status
    $http_status = $response['status']
        ?? ($response['success'] ? 200 : 500);
    unset($response['status']);


    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    http_response_code($http_status);
    echo json_encode($response);
    exit();
}

function is_valid_string(string $value): bool
{
    return preg_match('/^[a-zA-Z\s]+$/', $value) === 1;
}

function format_readable_time(string $time): string
{
    return date("g:i A", strtotime($time));
}

function format_readable_date(string $date): string
{
    if (empty($date)) return '';

    try {
        $dateObj = new DateTime($date);
        return $dateObj->format('d-M-Y');
    } catch (Exception $e) {
        log_error('formatReadableDate: Invalid date format - ' . $date);
        return '';
    }
}

function generate_random_string(int $length = 8): string
{
    $bytes = openssl_random_pseudo_bytes($length);
    return substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
}

function generate_random_number(int $length = 6): int
{
    $min = pow(10, $length - 1);
    $max = pow(10, $length) - 1;
    return random_int($min, $max);
}

function base_url(): string
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    // $script_name = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol. '://'. $host;
}