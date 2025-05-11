<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/logger.php';
require_once __DIR__ . '/../constants/system.php';


function configure_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        $sessionPath = dirname(__DIR__) . '/tmp/sessions';
        if (!is_dir($sessionPath)) {
            mkdir($sessionPath, 0777, true);
        }

        ini_set('session.save_handler', 'files');
        ini_set('session.save_path', $sessionPath);
    }
}

function begin_session(): void
{
    configure_session();

    // Determine the current environment
    $is_prod = env('APP_ENV') === 'production';

    // Set session options based on environment
    $session_options = [
        'cookie_lifetime' => 86400, // 1 day
        'cookie_httponly' => true,  // Prevent JavaScript access to cookies
        'use_strict_mode' => true,  // Prevent session fixation
        'use_only_cookies' => true, // Ensure sessions are only stored in cookies
        'cookie_samesite' => 'Strict', // Mitigate CSRF attacks ['Lax', 'Strict']
    ];

    if ($is_prod) {
        // Production environment: Secure cookie for HTTPS
        $session_options['cookie_secure'] = isset($_SERVER['HTTPS']);
    } else {
        // Development environment: No secure cookie, HTTP allowed
        $session_options['cookie_secure'] = false;
    }

    // Check if session is not started yet and then start it
    if (session_status() === PHP_SESSION_NONE) {
        session_start($session_options);
    }

    // Generate a new session ID if not already initiated
    generate_new_session_id();

    // Store session data to prevent tampering and hijacking
    if (!isset($_SESSION['ip_address'])) {
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
    }
    if (!isset($_SESSION['user_agent'])) {
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }
    if (!isset($_SESSION['session_hash'])) {
        $_SESSION['session_hash'] = hash('sha256', session_id() . $_SESSION['ip_address'] . $_SESSION['user_agent']);
    }
}

function generate_new_session_id(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_regenerate_id(true); // true to delete the old session
    }
}

function handle_session_data_tempering(): void
{
    $expected_hash = hash('sha256', session_id() . $_SESSION['ip_address'] . $_SESSION['user_agent']);
    if (!hash_equals($_SESSION['session_hash'], $expected_hash)) {
        log_error('Session data tampering detected!');

        terminate_session();
        header("Location: /auth/login");
        exit();
    }
}

function handle_session_hijacking(): void
{
    if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        log_error('Session hijacking detected!');

        terminate_session();
        header("Location: /auth/login");
        exit();
    }
}

function is_authenticated(): bool
{
    return isset($_SESSION['session_id']) && !is_null($_SESSION['session_id']);
}

function terminate_session(): void
{
    session_unset();    // Unset all session variables
    session_destroy();  // Destroy the session
    clearstatcache();   // Clear the file stat cache
}

function force_logout(): void
{
    terminate_session();
    echo "<script>window.location.href = '/auth/login';</script>";
    exit();
}

function is_admin(): bool
{
    return isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'Administrator';
}

function is_member(): bool
{
    return isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'Member';
}

function is_employee(): bool
{
    return isset($_SESSION['role_name']) && in_array($_SESSION['role_name'], EMPLOYEE_ROLES);
}