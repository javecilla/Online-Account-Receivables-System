<?php

declare(strict_types=1);

/**
 * Accounts Functions
 */

//TODO: add profile image upload
function create_account(array $data): array
{
    //return ['success' => true, 'message' => 'service: create_account'];
    try {
        $conn = open_connection();

        $account_uid = 'A' . generate_random_number(6);
        $hashed_password = hash_password($data['confirm_password']);

        $conn->begin_transaction();

        $sql = "INSERT INTO accounts (role_id, account_uid, email, username, `password`) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "issss",
            $data['role_id'],
            $account_uid,
            $data['email'],
            $data['username'],
            $hashed_password
        );

        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to create account', 'status' => 500];
        }

        $account_id = $stmt->insert_id;
        $conn->commit();
        return ['success' => true, 'message' => 'Account created successfully', 'data' => ['account_id' => $account_id]];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error creating account: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_accounts(int $page = 1, int $per_page = 10): array
{
    try {
        $conn = open_connection();

        $offset = ($page - 1) * $per_page;

        $base_query = vw_account_details();
        $count_sql = "SELECT COUNT(*) as total FROM accounts";
        $count_result = $conn->query($count_sql);
        $total_records = $count_result->fetch_assoc()['total'];

        $sql = $base_query . " ORDER BY a.account_id DESC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $per_page, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $accounts = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $total_pages = ceil($total_records / $per_page);
        $has_next = $page < $total_pages;
        $has_prev = $page > 1;

        return [
            'success' => true,
            'message' => 'Accounts retrieved successfully',
            'data' => [
                'items' => $accounts,
                'meta_data' => [
                    'current_page' => $page,
                    'per_page' => $per_page,
                    'total_records' => $total_records,
                    'total_pages' => $total_pages,
                    'has_next' => $has_next,
                    'has_prev' => $has_prev
                ]
            ]
        ];
    } catch (Exception $e) {
        log_error("Error fetching accounts: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_account(int $account_id): array
{
    try {
        $conn = open_connection();

        $base_query = vw_account_details();
        $sql = $base_query . " WHERE a.account_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => "Account with id {$account_id} is not found.", 'status' => 404];
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $result->fetch_assoc()];
    } catch (Exception $e) {
        log_error("Error fetching account: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

//TODO: add profile image upload
//TODO: make the updation dynamic, only update those fields is naka set
function update_account(int $account_id, array $data): array
{
    //return ['success' => true, 'message' => 'service: update_account'];
    try {
        $conn = open_connection();

        $hashed_password = hash_password($data['confirm_password']);

        $conn->begin_transaction();

        $sql = "UPDATE accounts SET role_id = ?, 
                email = ?, 
                username = ?, 
                `password` = ?
            WHERE `account_id` = ? LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssi",
            $data['role_id'],
            $data['email'],
            $data['username'],
            $hashed_password,
            $account_id
        );
        $updated = $stmt->execute();

        if (!$updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update account', 'status' => 500];
        }

        $conn->commit();

        $updated_account = get_account($account_id);
        return ['success' => true, 'message' => 'Account updated successfully', 'data' => $updated_account['data']];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating account: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function delete_account(int $account_id): array
{
    try {
        $conn = open_connection();

        $conn->begin_transaction();

        $sql = "DELETE FROM accounts WHERE `account_id` = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $account_id);
        $deleted = $stmt->execute();

        if (!$deleted) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to delete account', 'status' => 500];
        }

        $conn->commit();
        return ['success' => true, 'message' => 'Account deleted successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error deleting account: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function send_verification_email(string $email): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $code = generate_random_number(6);
        $key = generate_random_string(32);
        $expires_at = (new DateTime())->modify('+10 minutes')->format('Y-m-d H:i:s');
        $url = env('APP_URL');

        $sql = "INSERT INTO email_verification_codes (email, `code`, expires_at, created_at) 
        VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $email, $code, $expires_at);
        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to create verification code', 'status' => 500];
        }

        $verification_url = sprintf(
            "%s/auth/account-verification?key=%s&email=%s&code=%s&purpose=verify#external",
            $url,
            urlencode($key),
            urlencode($email),
            urlencode((string)$code)
        );

        $email_body_content = <<<HTML
            <p style="margin: 0; font-size: 12px; font-weight: bold; color: #000;">Good day, Ka-ITech,</p>
            <p style="margin: 5px 0; font-size: 12px; color: #000;">You're receiving this email because we've received a request to verify the account associated with this email address: <strong style="text-decoration: none; color: #000;">{$email}</strong>.</p>
            <p style="margin: 15px 0 10px 0; font-size: 12px; color: #000;">To complete the verification process, you can either:</p>
            
            <div style="margin: 20px 0; text-align: center; font-size: 20px; font-weight: bold; color: #000; padding: 5px; border-radius: 8px;">
                <a href="{$verification_url}" target="_blank" style="background-color: #2191e1; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-family: Arial, sans-serif; font-size: 16px;">Verify My Account</a>
            </div>

            <p style="text-align: center; font-size: 12px; color: #666; margin: 10px 0;">OR</p>
        
            <div style="margin: 10px 0; padding: 10px; background-color: #f5f5f5; border-radius: 5px;">
                <p style="margin: 0 0 5px 0; font-size: 12px; color: #666;">Copy and paste this link in your browser:</p>
                <p style="margin: 0; font-size: 11px; color: #2191e1; word-break: break-all;">{$verification_url}</p>
            </div>

            <p style="margin: 15px 0 5px 0; font-size: 12px; color: #000;">Your verification link is valid for the next <strong>10 minutes</strong>.</p>
            <p style="margin: 5px 0; font-size: 12px; color: #000;">If you did not initiate this request, you can safely ignore this email.</p>
        HTML;

        $mailed = send_email($email, 'Verify Your Account - Action Required', $email_body_content);
        if (!$mailed['success']) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Error sending verification email: ' . $mailed['message'], 'status' => 500];
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => "An account verification has been sent to your email {$email}. Please check your inbox, spams, or junk mail and use the code within 10 minutes.",
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error sending verification email: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function verify_account(string $email, int $code): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT * FROM email_verification_codes WHERE email = ? AND `code` = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Invalid verification code. Please try again.', 'status' => 400];
        }

        $verification = $result->fetch_assoc();
        if (strtotime($verification['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Verification code has expired. Please request a new code.', 'status' => 400];
        }

        $conn->begin_transaction();

        //mark verification code as used
        $evc_sql = "UPDATE email_verification_codes 
                SET used_at = NOW() 
                WHERE evc_id = ? AND used_at IS NULL LIMIT 1";
        $evc_stmt = $conn->prepare($evc_sql);
        $evc_stmt->bind_param('i', $verification['evc_id']);
        $evc_updated = $evc_stmt->execute();
        if (!$evc_updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to mark verification code as used', 'status' => 500];
        }

        //update account email verified
        $a_sql = "UPDATE accounts 
                SET email_verified_at = NOW()
                WHERE email = ? LIMIT 1";
        $a_stmt = $conn->prepare($a_sql);
        $a_stmt->bind_param('s', $email);
        $a_updated = $a_stmt->execute();
        if (!$a_updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update account status', 'status' => 500];
        }

        $conn->commit();

        return ['success' => true, 'message' => 'Account verified successfully', 'redirect' => '/auth/login'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error verifying account: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function send_otp_email(string $email): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $code = generate_random_number(6);
        $expires_at = (new DateTime())->modify('+5 minutes')->format('Y-m-d H:i:s');

        $sql = "INSERT INTO account_otp_codes (email, `code`, expires_at, created_at) 
        VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $email, $code, $expires_at);
        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to create otp code', 'status' => 500];
        }

        $email_body_content = <<<HTML
            <p style="margin: 0; font-size: 12px; font-weight: bold; color: #000;">Good day, Ka-ITech,</p>
            <p style="margin: 5px 0; font-size: 12px; color: #000;">Since this is your first time logging in, we need to verify your account for security purposes.</p>
            <p style="margin: 15px 0 10px 0; font-size: 12px; color: #000;">Your One-Time Password (OTP) is:</p>
            <div style="margin: 20px 0; text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #000; padding: 15px; border-radius: 5px; background: #f3f3f3;">
                {$code}
            </div>
            <p style="margin: 5px 0; font-size: 12px; color: #000;">This OTP will expire in <strong>5 minutes</strong>. If you did not request this OTP, please ignore this email.</p>
        HTML;

        $mailed = send_email($email, 'First Login Verification - OTP Required', $email_body_content);
        if (!$mailed['success']) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Error sending otp email: ' . $mailed['message'], 'status' => 500];
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => "A one-time password (OTP) has been sent to your email {$email}. Please check your inbox, spam or junk and enter the code within 5 minutes.",
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error sending OTP email: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function verify_otp(string $email, int $code): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT * FROM account_otp_codes WHERE email = ? AND `code` = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Invalid OTP code. Please try again.', 'status' => 400];
        }

        $otp = $result->fetch_assoc();
        if (strtotime($otp['expires_at']) < time()) {
            return ['success' => false, 'message' => 'OTP has expired. Please request a new OTP.', 'status' => 400];
        }

        $conn->begin_transaction();

        //mark otp code as used
        $aoc_sql = "UPDATE account_otp_codes 
            SET used_at = NOW() 
            WHERE aoc_id = ? AND used_at IS NULL LIMIT 1";
        $aoc_stmt = $conn->prepare($aoc_sql);
        $aoc_stmt->bind_param('i', $otp['aoc_id']);
        $aoc_updated = $aoc_stmt->execute();
        if (!$aoc_updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to mark OTP code as used', 'status' => 500];
        }

        //update account status to active and first login datetime
        $a_sql = "UPDATE accounts 
                SET account_status = 'active',
                    first_login_at = NOW() 
                WHERE email = ? AND `first_login_at` IS NULL LIMIT 1";
        $a_stmt = $conn->prepare($a_sql);
        $a_stmt->bind_param('s', $email);
        $a_updated = $a_stmt->execute();
        if (!$a_updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update account status', 'status' => 500];
        }

        $conn->commit();
        return ['success' => true, 'message' => 'OTP verified successfully', 'redirect' => '/dashboard'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error verifying OTP: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function send_password_reset_email(string $email): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $code = generate_random_number(6);
        $key = generate_random_string(32);
        $expires_at = (new DateTime())->modify('+5 minutes')->format('Y-m-d H:i:s');

        $sql = "INSERT INTO password_reset_codes (email, `code`, expires_at, created_at) 
        VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $email, $code, $expires_at);
        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to create password reset code', 'status' => 500];
        }

        $email_body_content = <<<HTML
            <p style="margin: 0; font-size: 12px; font-weight: bold; color: #000;">Good day, Ka-ITech,</p>
            <p style="margin: 5px 0; font-size: 12px; color: #000;">You are receiving this email because you requested a password reset. To continue reset your password, please use the code below:</p>
            <p style="margin: 15px 0 10px 0; font-size: 12px; color: #000;">Your password reset code is:</p>
            <div style="margin: 20px 0; text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #000; padding: 15px; border-radius: 5px; background: #f3f3f3;">
                {$code}
            </div>
            <p style="margin: 5px 0; font-size: 12px; color: #000;">This code will expire in <strong>5 minutes</strong>. If you did not request this password reset, please ignore this email.</p>
        HTML;

        $mailed = send_email($email, 'Password Reset Request', $email_body_content);
        if (!$mailed['success']) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Error sending password reset code email: ' . $mailed['message'], 'status' => 500];
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => "A password reset code has been sent to your email {$email}. Please check your inbox and enter the code within 5 minutes.",
            'data' => [
                'email' => $email,
                'key' => $key
            ]
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error sending password reset email: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function verify_password_reset_code(string $email, int $code): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT * FROM password_reset_codes WHERE email = ? AND `code` = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Invalid reset code code. Please try again.', 'status' => 400];
        }

        $reset_code = $result->fetch_assoc();
        if (strtotime($reset_code['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Reset code has expired. Please request a new reset code.', 'status' => 400];
        }

        $conn->begin_transaction();
        //mark reset code as used
        $prc_sql = "UPDATE password_reset_codes 
                SET used_at = NOW() 
                WHERE prc_id = ? AND used_at IS NULL LIMIT 1";
        $prc_stmt = $conn->prepare($prc_sql);
        $prc_stmt->bind_param('i', $reset_code['prc_id']);
        $prc_updated = $prc_stmt->execute();
        if (!$prc_updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to mark reset code as used', 'status' => 500];
        }

        $conn->commit();
        return ['success' => true, 'message' => 'Reset code verified successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error verifying password reset code: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function reset_password(string $email, string $new_password): array
{
    try {
        $conn = open_connection();

        $hashed_password = hash_password($new_password);

        $conn->begin_transaction();

        //update account password
        $sql = "UPDATE accounts 
                SET `password` = ? 
                WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $hashed_password, $email);
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to reset account password', 'status' => 500];
        }

        $conn->commit();
        return ['success' => true, 'message' => 'Password reset successfully. You may now login to your account.', 'redirect' => '/auth/login'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error resetting password: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function login_account(string $uid, string $password): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT * FROM accounts 
            WHERE BINARY username = ? OR BINARY account_uid = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $uid, $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        //check username existence
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Invalid username or password. Please try again.', 'status' => 400];
        }

        $account = $result->fetch_assoc();

        //check password 
        if (!password_verify($password, $account['password'])) {
            return ['success' => false, 'message' => 'Invalid username or password. Please try again.', 'status' => 400];
        }

        //Check if email is verified
        if ($account['email_verified_at'] === null) {
            return [
                'success' => false,
                'message' => 'Access to your account is currently restricted because your email address has not yet been verified. To proceed with verification, please initiate a verification request using the option provided below.',
                'status' => 401,
                'requires_verification' => true,
                'email' => $account['email']
            ];
        }

        //check for account first time login
        if ($account['first_login_at'] === null) {
            $mailed = send_otp_email($account['email']);
            if (!$mailed['success']) {
                return [
                    'success' => false,
                    'message' => $mailed['message'],
                ];
            }

            return [
                'success' => false,
                'message' => "A one-time password (OTP) has been sent to your registered email address, {$account['email']}. Please enter the OTP below to complete the verification process. Note that the OTP is valid for 5 minutes.",
                'status' => 401,
                'first_time_login' => true,
                'key' => generate_random_string(50),
                'email' => $account['email']
            ];
        }

        // Check if the account is inactive
        if ($account['account_status'] === INACTIVE) {
            return ['success' => false, 'message' => 'Your account is not active. Please contact support.', 'status' => 403];
        }

        $auth = get_account($account['account_id']);
        $auth_account = $auth['data'];

        $_SESSION['session_id'] = generate_new_session_id();
        $_SESSION['account_id'] = $auth_account['account_id'];
        $_SESSION['account_uid'] = $auth_account['account_uid'];
        $_SESSION['email'] = $auth_account['email'];
        $_SESSION['username'] = $auth_account['username'];
        $_SESSION['account_status'] = $auth_account['account_status'];
        $_SESSION['role_name'] = $auth_account['role_name'];

        //retrieve specific data base on the user role
        if ($auth_account['role_name'] === ADMINISTRATOR || $auth_account['role_name'] === ACCOUNTANT) {
            $e_result = get_employee_by_account($auth_account['account_id']);
            $employee = $e_result['data'];

            $_SESSION['employee_id'] = $employee['employee_id'];
            $_SESSION['first_name'] = $employee['first_name'];
            $_SESSION['last_name'] = $employee['last_name'];
            $_SESSION['middle_name'] = $employee['middle_name'];
            $_SESSION['full_name'] = $employee['full_name'];
            $_SESSION['contact_number'] = $employee['contact_number'];
        } else if ($auth_account['role_name'] === MEMBER) {
            $m_result = get_member_by_account($auth_account['account_id']);
            $member = $m_result['data'];

            $_SESSION['member_id'] = $member['member_id'];
            $_SESSION['first_name'] = $member['first_name'];
            $_SESSION['last_name'] = $member['last_name'];
            $_SESSION['middle_name'] = $member['middle_name'];
            $_SESSION['full_name'] = $member['full_name'];
            $_SESSION['contact_number'] = $member['contact_number'];
            $_SESSION['full_address'] = $member['full_address'];
        }

        log_request('Session Data: ', $_SESSION);
        return ['success' => true, 'message' => 'Login successful', 'redirect' => '/dashboard'];
    } catch (Exception $e) {
        log_error("Error logging in: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}


/**
 * Helpers
 */
function is_email_exist(string $email, ?int $account_id = null): bool
{
    try {
        $conn = open_connection();

        $sql = "SELECT 1 FROM accounts WHERE email = ?";

        // Add condition to exclude the current user ID if provided
        if ($account_id !== null) {
            $sql .= " AND `account_id` != ?";
        }

        $sql .= " LIMIT 1";

        $stmt = $conn->prepare($sql);

        //bind parameters dynamically based on whether $account_id is provided
        if ($account_id !== null) {
            $stmt->bind_param('si', $email, $account_id);
        } else {
            $stmt->bind_param('s', $email);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    } catch (Exception $e) {
        log_error("Error checking email existence: {$e->getMessage()}");
        return false;
    }
}

function is_username_exist(string $username, ?int $account_id = null): bool
{
    try {
        $conn = open_connection();

        $sql = "SELECT 1 FROM accounts WHERE username = ?";

        // Add condition to exclude the current user ID if provided
        if ($account_id !== null) {
            $sql .= " AND `account_id` != ?";
        }

        $sql .= " LIMIT 1";

        $stmt = $conn->prepare($sql);

        //bind parameters dynamically based on whether $account_id is provided
        if ($account_id !== null) {
            $stmt->bind_param('si', $username, $account_id);
        } else {
            $stmt->bind_param('s', $username);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    } catch (Exception $e) {
        log_error("Error checking username existence: {$e->getMessage()}");
        return false;
    }
}
