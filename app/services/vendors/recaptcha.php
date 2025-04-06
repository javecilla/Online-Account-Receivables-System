<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../config/logger.php';

function verify_recaptcha(string $recaptcha_response, string $user_ip): array
{
    // Google reCAPTCHA verification API request
    $uriAPI = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaServerKey = env('RECAPTCHA_BACKEND_KEY');
    $curlData = [
        'secret' => $recaptchaServerKey,
        'response' => $recaptcha_response,
        'remoteip' => $user_ip,
    ];

    // Initialize CURL request
    $curlOptConfig = [
        CURLOPT_URL => $uriAPI,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $curlData,
        CURLOPT_SSL_VERIFYPEER => false,
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $curlOptConfig);
    $curlResponse = curl_exec($ch);
    if (curl_errno($ch)) {
        log_error('Curl error: ' . curl_error($ch));
        return ['success' => false, 'message' => 'Curl error: ' . curl_error($ch), 'status' => 500];
    }
    curl_close($ch);

    // Decode JSON data of API response array
    $captchaResponse = json_decode($curlResponse);
    if (empty($captchaResponse)) {
        return ['success' => false, 'message' => 'Captcha error! Please try again later.', 'status' => 500];
    }

    return ['success' => true, 'message' => 'Recaptcha validated', 'status' => 200];
}
