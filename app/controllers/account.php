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

function handle_get_accounts_by_criteria(mixed $payload): void {
    $validated = validate_data($payload, [
        'roles' => 'required', //|string|in:Administrator,Accountant,Member
        'status' => 'required', //|string|in:active,inactive
        'verification' => 'required', //|string|in:verified,unverified
    ]);

    $role_array = array_map('trim', explode(',', $validated['data']['roles']));
    foreach ($role_array as $role) {
        if (!in_array($role, ACCOUNT_ROLES)) {
            return_response([
                'success' => false,
                'message' => "Invalid role value: {$role}",
                'status' => 400
            ]);
        }
    }

    $status_array = array_map('trim', explode(',', $validated['data']['status']));
    foreach ($status_array as $status) {
        if (!in_array($status, ACCOUNT_STATUS)) {
            return_response([
                'success' => false,
                'message' => "Invalid status value: {$status}",
                'status' => 400
            ]);
        }
    }

    $verification_array = array_map('trim', explode(',', $validated['data']['verification']));
    foreach ($verification_array as $verification) {
        if (!in_array($verification, ACCOUNT_VERIFICATION)) {
            return_response([
                'success' => false,
                'message' => "Invalid verification value: {$verification}",
                'status' => 400
            ]);
        }
    }

    $accounts = get_accounts_by_criteria($role_array, $status_array, $verification_array);
    return_response($accounts);
}

function handle_update_account_status(mixed $payload): void
{
    $validated = validate_data($payload, [
        'account_id' => 'required|numeric|min:1|check:account_model',
        'status' => 'required|in:active,inactive',
        'email' => 'optional|email|check:is_email_exist',
        'title' => 'optional',
        'message' => 'optional',
        'is_sending_email' =>'optional|boolean'
    ]);

    // log_request('payload', $validated['data']);
    // return_response(['success' => true,'message' => 'test']);

    $account_id = (int)$validated['data']['account_id'];
    $new_status = $validated['data']['status'];

    $send_email_flag = $validated['data']['is_sending_email'] ?? false;

    if($send_email_flag) {
        $recipient_email = $validated['data']['email'];
        $custom_title = empty($validated['data']['title']) ? null : htmlspecialchars($validated['data']['title']);
        $custom_message = empty($validated['data']['message']) ? null : nl2br(htmlspecialchars($validated['data']['message']));

        $email_subject = '';
        $email_message = '';

        switch ($new_status) {
            case ACTIVE:
                $email_subject = $custom_title ?? "Account Activated";
                $email_message = $custom_message ?? <<<HTML
                    <p>Your account has been successfully activated.</p>
                    <p>You can now fully utilize all the benefits and services associated with your account.</p>
                    <p>If you have any questions, please feel free to contact us.</p>
                HTML;
                break;

            case INACTIVE:
            default:
                $email_subject = $custom_title?? "Account Deactivated";
                $email_message = $custom_message?? <<<HTML
                    <p>Your account has been deactivated.</p>
                    <p>You can reactivate your account at any time by logging in and following the instructions.</p>
                    <p>If you have any questions, please feel free to contact us.</p>
                HTML;
                break;
        }

        $email_body_content = <<<HTML
            <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                <h2 style="color: #0056b3;">Account Status Update</h2>
                <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
                $email_message
                <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
                <p>Thank you,<br>OARSMC Avecilla</p>
                <p style="font-size: 0.8em; color: #777;">If you believe you received this email in error, please disregard it or contact our support immediately.</p>
                <p style="font-size: 0.8em; color: #777;">This is an automated message. Please do not reply directly to this email.</p>
            </div>
        HTML;

        $mailed = send_email($recipient_email, $email_subject, $email_body_content);
        if (!$mailed['success']) {
            return_response(['success' => false, 'message' => 'Failed to send email: ' . $mailed['message'], 'status' => 500]);
        }
    }

    $updated = update_account_status($account_id, $new_status);
    return_response($updated);
}