<?php

declare(strict_types=1);

function handle_create_member(mixed $payload): void
{
    $validated = validate_data($payload, [
        'type_id' => 'required|numeric|min:1|check:type_model',
        'first_name' => 'required|string',
        'middle_name' => 'optional|string',
        'last_name' => 'required|string',
        'contact_number' => 'required|length:13',
        'house_address' => 'required',
        'barangay' => 'required',
        'municipality' => 'required|string',
        'province' => 'required|string',
        'region' => 'required',
        'page_from' => 'required'
    ]);

    // log_request('payload validated', $validated['data']);
    // return_response(['success' => true, 'message' => 'test']);
    $created = create_member($validated['data']);
    return_response($created);
}

//TODO:
function handle_create_member_cooperative(mixed $payload): void
{
    //log_request('payload', $payload);
    $validated = validate_data($payload, [
        //account information
        'role_id' => 'required|numeric|min:1|check:role_model',
        'email'   => 'required|email|check:is_email_unique',
        'username' => 'required|check:is_username_unique',
        'password' => 'required|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'required|match:password',
        //'profile_img'   => 'required|image|mimes:jpeg,jpg,png|size:1048576',
        //member information
        'type_id' => 'required|numeric|min:1|check:type_model',
        'first_name' => 'required|string',
        'middle_name' => 'optional|string',
        'last_name' => 'required|string',
        'contact_number' => 'required|length:13',
        'house_address' => 'required',
        'barangay' => 'required',
        'municipality' => 'required|string',
        'province' => 'required|string',
        'region' => 'required',
        'page_from' => 'required'
    ]);

    //return_response(['success' => true, 'message' => '[TEST]: Member Registered successfully.']);

    $a_created = create_account($validated['data']);
    if (!$a_created['success']) {
        return_response($a_created);
    }

    $validated['data']['account_id'] = $a_created['data']['account_id'];

    $m_created = create_member($validated['data']);
    if (!$m_created['success']) {
        return_response($m_created);
    }

    $response_message = 'Registered successfully. ';
    $page_from = $validated['data']['page_from'];
    //check if the account creation came from registration in client page
    if ($page_from === 'external') {
        $mailed = send_verification_email($validated['data']['email']);
        if (!$mailed['success']) {
            return_response($mailed);
        }
        $response_message .= $mailed['message'];
    }

    return_response(['success' => true, 'message' => $response_message]);
}

function handle_get_members(mixed $payload): void
{
    $validated = validate_data($payload, [
        'page' => 'optional|numeric|min:1',
        'per_page' => 'optional|numeric|min:1'
    ]);

    $page = isset($validated['data']['page']) ? (int)$validated['data']['page'] : 1;
    $per_page = isset($validated['data']['per_page']) ? (int)$validated['data']['per_page'] : 100;

    $members = get_members($page, $per_page);
    return_response($members);
}

function handle_get_member(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1',
    ]);
    $member = get_member((int)$validated['data']['member_id']);
    return_response($member);
}

function handle_get_member_by_account(mixed $payload): void
{
    $validated = validate_data($payload, [
        'account_id' => 'required|numeric|min:1|check:account_model',
    ]);
    $member = get_member_by_account((int)$validated['data']['account_id']);
    return_response($member);
}

function handle_update_member(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'type_id' => 'required|numeric|min:1|check:type_model',
        'first_name' => 'required|string',
        'middle_name' => 'optional|string',
        'last_name' => 'required|string',
        'contact_number' => 'required|length:13',
        'house_address' => 'required',
        'barangay' => 'required',
        'municipality' => 'required|string',
        'province' => 'required|string',
        'region' => 'required',
        'membership_status' => 'required|string|in:active,inactive,suspended,closed'
    ]);

    $updated = update_member($validated['data']['member_id'], $validated['data']);
    return_response($updated);
}

function handle_update_member_cooperative(mixed $paylod): void
{
    $validated = validate_data($paylod, [
        //acount info
        'account_id' => 'required|numeric|min:1|check:account_model',
        'role_id' => 'required|numeric|min:1|check:role_model',
        'email'   => 'required|email|check_except:is_email_unique,account_id',
        'username' => 'required|check_except:is_username_unique,account_id',
        'password' => 'optional|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'optional|match:password',
        //member info
        'member_id' => 'required|numeric|min:1|check:member_model',
        'type_id' => 'required|numeric|min:1|check:type_model',
        'first_name' => 'required|string',
        'middle_name' => 'optional|string',
        'last_name' => 'required|string',
        'contact_number' => 'required|length:13',
        'house_address' => 'required',
        'barangay' => 'required',
        'municipality' => 'required|string',
        'province' => 'required|string',
        'region' => 'required',
    ]);
    log_request('payload', $validated['data']);
    // return_response(['success' => true, 'message' => 'testtt update']);

    $a_updated = update_account((int)$validated['data']['account_id'], $validated['data']);
    if (!$a_updated['success']) {
        return_response($a_updated);
    }

    $m_updated = update_member((int)$validated['data']['member_id'], $validated['data']);
    if (!$m_updated['success']) {
        return_response($m_updated);
    }

    return_response(['success' => true, 'message' => 'Updated successfully.']);
}

// TODO: email members for their membership status if status 'closed'
function handle_update_member_status(mixed $payload): void
{
    log_request('payload', $payload);
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'membership_status' => 'required|string|in:active,inactive,suspended,closed',
        'is_sending_email' =>'optional|boolean',
        'email' => 'optional|email|check:is_email_exist',
        'title' => 'optional',
        'message' => 'optional',
        'member_name' => 'optional'
    ]);

    //return_response(['success' => true,'message' => 'test update']);

    $member_id = (int)$validated['data']['member_id'];
    $new_status = $validated['data']['membership_status'];

    $send_email_flag = $validated['data']['is_sending_email'] ?? false;

    if($send_email_flag) {
        $recipient_name = htmlspecialchars($validated['data']['member_name']);
        $recipient_email = $validated['data']['email'];
        $custom_title = empty($validated['data']['title']) ? null : htmlspecialchars($validated['data']['title']);
        $custom_message = empty($validated['data']['message']) ? null : nl2br(htmlspecialchars($validated['data']['message']));

        $email_subject = '';
        $email_message = '';

        switch ($new_status) {
            case ACTIVE:
                $email_subject = $custom_title ?? "Your OARSMC Avecilla Membership is Now Active";
                $email_message = $custom_message ?? <<<HTML
                    <p>We are pleased to inform you that your membership status with OARSMC Avecilla has been updated to <strong>Active</strong>.</p>
                    <p>You can now fully utilize all the benefits and services associated with your account.</p>
                    <p>If you have any questions, please feel free to contact us.</p>
                HTML;
                break;
            
            case INACTIVE:
                $email_subject = $custom_title ?? "Important Update Regarding Your OARSMC Avecilla Membership";
                $email_message = $custom_message ?? <<<HTML
                    <p>This email is to inform you that your membership status with OARSMC Avecilla has been set to <strong>Inactive</strong>.</p>
                    <p>This may be due to inactivity, pending documentation. While your account is inactive, certain services may be limited.</p>
                    <p>Please contact our support team if you wish to reactivate your account or have any questions regarding this status change.</p>
                HTML;
               break;

            case SUSPENDED:
                $email_subject = $custom_title ?? "Urgent: Your OARSMC Avecilla Membership Has Been Suspended";
                $email_message = $custom_message ?? <<<HTML
                    <p>This is an important notification regarding your OARSMC Avecilla membership. Your account status has been updated to <strong>Suspended</strong>.</p>
                    <p>Account suspension typically occurs due to policy violation, outstanding dues. Access to your account and related services will be restricted during the suspension period.</p>
                    <p>We urge you to contact us as soon as possible to understand the reason for suspension and the steps required for reinstatement.</p>
                HTML;
                break;

            case CLOSED:
                $email_subject = $custom_title ?? "Confirmation: Your OARSMC Avecilla Membership Has Been Closed";
                $email_message = $custom_message ?? <<<HTML
                    <p>This email confirms that your membership with OARSMC Avecilla has been officially <strong>Closed</strong>.</p>
                    <p>This action may have been initiated based on your request or due to prolonged inactivity, specific terms.</p>
                    <p>If you believe this closure was made in error or have any final inquiries, please contact us within [mention timeframe, e.g., 30 days]. We thank you for your past membership.</p>
                HTML;
                break;

            default:
                return_response(['success' => false,'message' => 'Something went wrong','status' => 400]);
                break;
        }

        $email_body_content = <<<HTML
            <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                <h2 style="color: #0056b3;">Membership Status Update</h2>
                <p>Dear {$recipient_name},</p>
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

    $status_update = update_membership_status($member_id, $new_status);
    return_response($status_update);
}

function handle_delete_member(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
    ]);

    //check if member has active amortizations before deleting
    $active_check = check_active_amortizations($validated['data']['member_id']);
    if (!$active_check['success']) {
        return_response($active_check);
    }

    if ($active_check['has_active']) {
        return_response([
            'success' => false,
            'message' => "Cannot delete member becuase this member has {$active_check['active_count']} active amortization(s).",
            'status' => 400
        ]);
    }

    $deleted = delete_member((int) $validated['data']['member_id']);
    return_response($deleted);
}


// require_once __DIR__ . '/transaction.php';

function handle_deposit_transaction(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'amount' => 'required|numeric|min:100'
    ]);

    $member_id = (int)$validated['data']['member_id'];
    $amount = (float)$validated['data']['amount'];

    $member = get_member($member_id);
    if (!$member['success']) {
        return_response($member);
    }

    // Check if account type is loan
    if (strtolower($member['data']['membership_type']) === 'loan') {
        return_response([
            'success' => false,
            'message' => 'Deposit operations are not allowed for loan accounts',
            'status' => 400
        ]);
    }

    $transaction = update_balance($member_id, $amount, DEPOSIT);
    return_response($transaction);
}

function handle_withdrawal_transaction(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'amount' => 'required|numeric|min:100'
    ]);

    $member_id = (int)$validated['data']['member_id'];
    $amount = (float)$validated['data']['amount'];

    $member = get_member($member_id);
    if (!$member['success']) {
        return_response($member);
    }

    if (strtolower($member['data']['membership_type']) === 'loan') {
        return_response([
            'success' => false,
            'message' => 'Withdrawal operations are not allowed for loan accounts',
            'status' => 400
        ]);
    }

    $current_balance = $member['data']['current_balance'];
    $minimum_balance = $member['data']['minimum_balance'];

    //check if withdrawal amount exceeds current balance
    if ($amount > $current_balance) {
        return_response([
            'success' => false,
            'message' => 'Insufficient balance for withdrawal.',
            'data' => [
                'current_balance' => $current_balance,
                'withdrawal_amount' => $amount,
                'maximum_withdrawal' => $current_balance - $minimum_balance
            ],
            'status' => 400
        ]);
    }

    //check if withdrawal would violate minimum balance
    $new_balance = $current_balance - $amount;
    if ($new_balance < $minimum_balance) {
        return_response([
            'success' => false,
            'message' => 'Transaction failed. Withdrawal would put account below minimum balance.',
            'data' => [
                'current_balance' => $current_balance,
                'withdrawal_amount' => $amount,
                'minimum_balance' => $minimum_balance,
                'maximum_withdrawal' => $current_balance - $minimum_balance
            ],
            'status' => 403
        ]);
    }

    $transaction = update_balance($member_id, $amount, WITHDRAWAL);
    return_response($transaction);
}

function handle_credit_interest(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model'
    ]);

    $member_id = (int)$validated['data']['member_id'];

    // Note: We allow interest transactions for all account types including loans
    $interest = credit_interest($member_id);
    return_response($interest);
}

function handle_check_and_apply_penalties(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model'
    ]);

    $member_id = (int)$validated['data']['member_id'];

    // Note: We allow fee transactions for all account types including loans
    $balance_check = check_minimum_balance($member_id);
    if (!$balance_check['success']) {
        return_response($balance_check);
    }

    if ($balance_check['data']['is_below_minimum']) {
        $penalty = apply_account_fees($member_id);
        return_response($penalty);
    }

    return_response([
        'success' => true,
        'message' => 'Account is maintaining required balance',
        'data' => $balance_check['data']
    ]);
}

function handle_get_membership_types(mixed $payload): void
{
    $roles = get_membership_types();
    return_response($roles);
}

function handle_get_member_transactions(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
    ]);

    $transactions = get_member_transactions((int)$validated['data']['member_id']);
    return_response($transactions);
}

function handle_get_members_transactions(mixed $payload): void
{
    $transactions = get_members_transactions();
    return_response($transactions);
}

function handle_get_members_by_criteria(mixed $payload): void
{
    //log_request('data:', $payload);
    $validated = validate_data($payload, [
        'membership_types' => 'required', //in:Savings Account,Time Deposit,Fixed Deposit,Special Savings,Youth Savings,Loan
        'membership_status' => 'required' //in:active,inactive,suspended,closed
    ]);

    $mt_array = array_map('trim', explode(',', $validated['data']['membership_types']));
    foreach ($mt_array as $type) {
        if (!in_array($type, MEMBERSHIP_TYPES)) {
            return_response([
                'success' => false,
                'message' => "Invalid membership type value: {$type}",
                'status' => 400
            ]);
        }
    }

    $ms_array = array_map('trim', explode(',', $validated['data']['membership_status']));
    foreach ($ms_array as $status) {
        if (!in_array($status, MEMBERSHIP_STATUS)) {
            return_response([
                'success' => false,
                'message' => "Invalid membership status value: {$status}",
                'status' => 400
            ]);
        }
    }

    $amortizations = get_members_by_criteria($mt_array, $ms_array);
    return_response($amortizations);
}

// MEMBER METRICS
function handle_get_member_account_balance_metrics(mixed $payload): void
{
    //log_request('payload', $payload);
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
    ]);

    $metrics = get_member_account_balance_metrics((int)$validated['data']['member_id']);
    return_response($metrics);
}

function handle_get_member_savings_goal_metrics(mixed $payload): void
{
    //log_request('payload', $payload);
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
    ]);

    $metrics = get_member_savings_goal_metrics((int)$validated['data']['member_id']);
    return_response($metrics);
}

function handle_get_member_active_loans_metrics(mixed $payload): void
{
    //log_request('payload', $payload);
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
    ]);

    $metrics = get_member_active_loans_metrics((int)$validated['data']['member_id']);
    return_response($metrics);
}

function handle_get_member_account_status_metrics(mixed $payload): void
{
    //log_request('payload', $payload);
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
    ]);

    $metrics = get_member_account_status_metrics((int)$validated['data']['member_id']);
    return_response($metrics);
}

function handle_get_member_upcoming_payments(mixed $payload): void
{
    //log_request('payload', $payload);
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
    ]);

    $metrics = get_member_upcoming_payments((int)$validated['data']['member_id']);
    return_response($metrics);
}