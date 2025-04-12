<?php

declare(strict_types=1);

//TODO: add profile image upload
function handle_create_account(mixed $payload): void
{
    $validated = validate_data($payload, [
        'role_id' => 'required|numeric|min:1|check:role_model',
        'email'   => 'required|email|check:is_email_unique',
        'username' => 'required|check:is_username_unique',
        'password' => 'required|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'required|match:password',
        //'profile_img'   => 'required|image|mimes:jpeg,jpg,png|size:1048576',
        'page_from' => 'required'
    ]);

    $created = create_account($validated['data']);
    if (!$created['success']) {
        return_response($created);
    }

    $response_message = $created['message'];
    $page_from = $validated['data']['page_from'];
    //check if the account creation came from registration in client page
    if ($page_from === 'registration') {
        $mailed = send_verification_email($validated['data']['email']);
        if (!$mailed['success']) {
            return_response($mailed);
        }
        $response_message .= ' ' . $mailed['message'];
    }


    return_response(['success' => true, 'message' => $response_message]);
}

function handle_get_accounts(mixed $payload): void
{
    $validated = validate_data($payload, [
        'page' => 'optional|numeric|min:1',
        'per_page' => 'optional|numeric|min:1'
    ]);

    $page = isset($validated['data']['page']) ? (int)$validated['data']['page'] : 1;
    $per_page = isset($validated['data']['per_page']) ? (int)$validated['data']['per_page'] : 100;

    $accounts = get_accounts($page, $per_page);
    return_response($accounts);
}

function handle_get_account(mixed $payload): void
{
    $validated = validate_data($payload, [
        'account_id' => 'required|numeric|min:1',
    ]);
    $account = get_account((int) $validated['data']['account_id']);
    return_response($account);
}

//TODO: add profile image upload
function handle_update_account(mixed $payload): void
{
    $validated = validate_data($payload, [
        'account_id' => 'required|numeric|min:1|check:account_model',
        'role_id' => 'required|numeric|min:1|check:role_model',
        'email'   => 'required|email|check_except:is_email_unique,account_id',
        'username' => 'required|check_except:is_username_unique,account_id',
        'password' => 'required|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'required|match:password'
        //'profile_img'   => 'required|image|mimes:jpeg,jpg,png|size:1048576',
    ]);

    $updated = update_account($validated['data']['account_id'], $validated['data']);
    return_response($updated);
}

function handle_delete_account(mixed $payload): void
{
    $validated = validate_data($payload, [
        'account_id' => 'required|numeric|min:1|check:account_model',
    ]);
    //log_request('payloadvalidated', $validated['data']);

    $deleted = delete_account((int) $validated['data']['account_id']);
    return_response($deleted);
}

function handle_verify_account(mixed $payload): void
{
    $validated = validate_data($payload, [
        'email' => 'required|email|check:is_email_exist',
        'code' => 'required|length:6|numeric',
    ]);

    $email = $validated['data']['email'];
    $code = (int)$validated['data']['code'];
    $verified = verify_account($email, $code);
    return_response($verified);
}

function handle_request_account_verification(mixed $payload): void
{
    $validated = validate_data($payload, [
        'email' => 'required|email|check:is_email_exist',
        'recaptcha_response' => 'required'
    ]);

    $verified = verify_recaptcha($validated['data']['recaptcha_response'], get_client_ip());
    if(!$verified['success']) {
        return_response($verified);
    }

    $email = $validated['data']['email'];
    $mailed = send_verification_email($email);
    return_response($mailed);
}

function handle_request_otp(mixed $payload): void
{
    $validated = validate_data($payload, [
        'email' => 'required|email|check:is_email_exist',
    ]);

    $email = $validated['data']['email'];
    $mailed = send_otp_email($email);
    return_response($mailed);
}

function handle_verify_otp(mixed $payload): void
{
    $validated = validate_data($payload, [
        'email' => 'required|email|check:is_email_exist',
        'code' => 'required|length:6|numeric',
    ]);

    $email = $validated['data']['email'];
    $code = (int)$validated['data']['code'];
    $verified = verify_otp($email, $code);
    return_response($verified);
}

function handle_request_password_reset(mixed $payload): void
{
    $validated = validate_data($payload, [
        'email' => 'required|email|check:is_email_exist',
    ]);

    $email = $validated['data']['email'];
    $mailed = send_password_reset_email($email);
    return_response($mailed);
}

function handle_verify_reset_code(mixed $payload): void
{
    $validated = validate_data($payload, [
        'email' => 'required|email|check:is_email_exist',
        'code' => 'required|length:6|numeric',
    ]);

    $email = $validated['data']['email'];
    $code = $validated['data']['code'];
    $verified = verify_password_reset_code($email, $code);
    return_response($verified);
}

function handle_reset_password(mixed $payload): void
{
    $validated = validate_data($payload, [
        'email' => 'required|email|check:is_email_exist',
        'password' => 'required|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'required|match:password'
    ]);

    $email = $validated['data']['email'];
    $new_password = $validated['data']['confirm_password'];
    $reset = reset_password($email, $new_password);
    return_response($reset);
}

function handle_login_account(mixed $payload): void
{
    $validated = validate_data($payload, [
        'uid' => 'required',
        'password' => 'required',
        'recaptcha_response' => 'required'
    ]);

    $verified = verify_recaptcha($validated['data']['recaptcha_response'], get_client_ip());
    if(!$verified['success']) {
        return_response($verified);
    }

    //return_response(['success' => true, 'message' => 'login success testtt']);

    $uid = trim(htmlspecialchars($validated['data']['uid']));
    $password = trim(htmlspecialchars($validated['data']['password']));
    $login = login_account($uid, $password);
    return_response($login);
}

function handle_logout_account(mixed $payload): void
{
    terminate_session();
    return_response(['success' => true, 'message' => 'Logout successful', 'redirect' => '/auth/login']);
}

function handle_get_account_roles(mixed $payload): void
{
    $roles = get_account_roles();
    return_response($roles);
}
