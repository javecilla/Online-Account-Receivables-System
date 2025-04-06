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
    if ($page_from === 'registration') {
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
    $per_page = isset($validated['data']['per_page']) ? (int)$validated['data']['per_page'] : 10;

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

// TODO: email members for their membership status if status 'closed'
function handle_update_member_status(mixed $payload): void
{
    /**
     * Handles membership status changes
     * #: Suspend member for policy violation
     */
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'membership_status' => 'required|string|in:active,inactive,suspended,closed',
        //'reason' => 'required|string'
    ]);

    $member_id = (int)$validated['data']['member_id'];
    $new_status = $validated['data']['membership_status'];
    //$reason = $validated['data']['reason'];

    $status_update = update_membership_status($member_id, $new_status);
    return_response($status_update);
}

function handle_delete_member(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
    ]);

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
