<?php

declare(strict_types=1);

function get_user_location(string $userIp): array
{
    $testIp = '119.94.129.74';
    $remoteip = ($userIp === '127.0.0.1' || $userIp === '::1') ? $testIp : $userIp;

    $accessToken = '8891193c7a6adb';
    $url = "https://ipinfo.io/{$remoteip}?token={$accessToken}";

    $response = @file_get_contents($url); // Suppress errors
    if ($response === FALSE) {
        return ['success' => false, 'message' => 'Failed to retrieve location data.', 'status' => 500];
        // return null; // Return null if API request fails
    }

    $locationData = json_decode($response, true);
    return ['success' => true, 'message' => 'Location retrieved successfully.', 'data' => $locationData, 'status' => 200];
    //return $locationData;
}
