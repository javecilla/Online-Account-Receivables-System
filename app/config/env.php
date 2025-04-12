<?php

declare(strict_types=1);

require_once __DIR__ . '/logger.php';

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
*/
try {
    $config = [
        // Application
        'APP_AUTHOR' => 'Jerome Avecilla',
        'APP_NAME' => 'Online Accounts Receivable System for a Multipurpose Cooperative',
        'APP_DESCRIPTION' => 'A web-based application for managing accounts receivable in a cooperative setting.',
        'APP_ENV' => 'local',
        'APP_KEY' => '',
        'APP_URL' => 'http://jerome-avecilla-preview.infinityfreeapp.test',
        'APP_TIMEZONE' => 'Asia/Manila',

        // Database
        'DB_CONNECTION' => 'mysql',
        'DB_PORT' => 3306,
        'DB_HOST' => '127.0.0.1',       # sql200.infinityfree.com | 127.0.0.1
        'DB_DATABASE' => 'oarsmc_db',   # if0_38669040_oarsmc_db | oarsmc_db
        'DB_USERNAME' => 'root',        # if0_38669040 | root
        'DB_PASSWORD' => '4545',        # d5bBL6cbuR | 4545

        // Mail
        'MAIL_MAILER' => 'smtp',
        'MAIL_HOST' => 'smtp.gmail.com',
        'MAIL_PORT' => 587,
        'MAIL_USERNAME' => 'jeromesavc@gmail.com',
        'MAIL_PASSWORD' => 'zumofrbbxcubyzlg',
        'MAIL_ENCRYPTION' => 'tls',
        'MAIL_FROM_SENDER' => 'jeromesavc@gmail.com',
        'MAIL_FROM_ADDRESS' => 'Bulacan, Philippines',
        'MAIL_FROM_NAME' => 'Jerome Avecilla - BSIT 2FG2',

        // Google ReCAPTCHA
        # 6LfblxUrAAAAAMq0E2qE_nPySMkmT3OE7YIkeRjB | 6Lc0rhMrAAAAADyUbPgxPT6ePnC9D9wigiyNresR
        'RECAPTCHA_FRONTEND_KEY' => '6Lc0rhMrAAAAADyUbPgxPT6ePnC9D9wigiyNresR',
        # 6LfblxUrAAAAAOV6eKmHNTKuJLXW84X0gEK_iY5F | 6Lc0rhMrAAAAADb2su_7kCyBaUWfAvWKgPq4VyEF
        'RECAPTCHA_BACKEND_KEY' => '6Lc0rhMrAAAAADb2su_7kCyBaUWfAvWKgPq4VyEF',

        // Google Site Verification
        'GOOGLE_SITE_VERIFICATION' => 'RAw3XoeQlQcldgqkMijkj8jMucAoJTaY_Ns44cioVfM',
    ];

    foreach ($config as $key => $value) {
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
} catch (Exception $e) {
    log_error("Error loading environment: " . $e->getMessage());
}

function env(string $key, $default = null)
{
    return $_ENV[$key] ?? getenv($key) ?: $default;
}
