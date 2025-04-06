<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../config/logger.php';
require_once __DIR__ . '/global.php';

function hash_password(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT);
}

function get_server_request_data(): ?array
{
    # Retrieve the raw POST input
    $rawInput = file_get_contents('php://input');
    log_request('get_server_request_data(): ', ['rawInput' => $rawInput]);

    # Decode the JSON input
    $request = json_decode($rawInput, true);
    log_request('request: ', $request);

    # Check if the JSON decoding was successful
    if (is_null($request)) {
        return null;
    }

    return $request;
}

function get_query_params(): array
{
    return $_GET;
}

function get_post_params(): array
{
    return $_POST;
}

function get_request_method(): string
{
    return $_SERVER['REQUEST_METHOD'];
}

function get_request_uri(): string
{
    return $_SERVER['REQUEST_URI'];
}

function get_request_headers(): array
{
    return getallheaders();
}

function get_protocol(): string
{
    return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
}

function get_domain(): string
{
    return $_SERVER['HTTP_HOST'];
}

function get_full_url(): string
{
    return get_protocol() . get_domain() . get_request_uri();
}

function get_base_url(): string
{
    return get_protocol() . get_domain();
}

function get_request_file_name(): string
{
    return basename($_SERVER['PHP_SELF']);
}

function generate_member_id(int $role_id): string
{
    $prefix = match ($role_id) {
        1 => 'ADMIN',
        2 => 'ACC',
        3 => 'MEM',
        default => 'USR'
    };

    return $prefix . str_pad((string)mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
}
