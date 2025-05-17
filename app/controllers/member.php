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
        'caid' => 'required|numeric|min:1',
        'amount' => 'required|numeric|min:100'
    ]);

    $member_id = (int)$validated['data']['member_id'];
    $amount = (float)$validated['data']['amount'];

    $member = get_member($member_id);
    if (!$member['success']) {
        return_response($member);
    }

    $validated['data']['transaction_type'] = DEPOSIT;

    $transaction = update_balance($validated['data']);
    return_response($transaction);
}

function handle_withdrawal_transaction(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'caid' => 'required|numeric|min:1',
        'amount' => 'required|numeric|min:100'
    ]);

    $member_id = (int)$validated['data']['member_id'];
    $caid = (int)$validated['data']['caid'];
    $amount = (float)$validated['data']['amount'];

    $member = get_registered_member($member_id);
    if (!$member['success']) {
        return_response($member);
    }

    // $coop = get_cooperative_account($caid);
    // if (!$coop['success']) {
    //     return_response($coop);
    // }
    //$coop['data']['membership_type']; e.g., Savings Account, Fixed Deposit, etc.

    $current_balance = $member['data']['current_balance'];
    //$minimum_balance = $member['data']['minimum_balance'];

    //$member['data']['cooperative_accounts'];

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

    $validated['data']['transaction_type'] = WITHDRAWAL;

    $transaction = update_balance($validated['data']);
    return_response($transaction);
}

function handle_maturity_withdrawal_transaction(mixed $payload): void 
{ 
    $validated = validate_data($payload, [ 
        'member_id' => 'required|numeric|min:1|check:member_model', 
        'caid' => 'required|numeric|min:1', 
        'amount' => 'required|numeric|min:100' 
    ]); 

    $member_id = (int)$validated['data']['member_id']; 
    $caid = (int)$validated['data']['caid']; 
    $amount = (float)$validated['data']['amount']; 

    // Get cooperative account details 
    $coop = get_cooperative_account($caid); 
    if (!$coop['success']) { 
        return_response($coop); 
    } 
    
    // Check if account type is Fixed or Time Deposit 
    if ($coop['data']['membership_type'] != 'Fixed Deposit' && $coop['data']['membership_type'] != 'Time Deposit') { 
        return_response([ 
            'success' => false, 
            'message' => 'This withdrawal type is only for Fixed or Time Deposit accounts', 
            'status' => 400 
        ]); 
    } 
    
    // Check maturity date 
    $maturity_date = calculate_maturity_date($coop['data']['opened_date'], $coop['data']['membership_id']); 
    $current_date = date('Y-m-d'); 
    
    $is_mature = strtotime($current_date) >= strtotime($maturity_date); 
    
    // Apply early withdrawal penalty if not mature 
    if (!$is_mature) { 
        // Calculate penalty 
        $penalty_rate = $coop['data']['penalty_rate']; 
        $penalty_amount = $amount * $penalty_rate; 
        
        // Ensure penalty is within bounds 
        $penalty_amount = max( 
            $coop['data']['minimum_penalty_fee'], 
            min($penalty_amount, $coop['data']['maximum_penalty_fee']) 
        ); 
        
        // Add penalty note 
        $validated['data']['notes'] = 'Early withdrawal penalty applied: ' . 
            number_format($penalty_amount, 2) . ' (' . 
            ($penalty_rate * 100) . '% of withdrawal amount)'; 
            
        // Create notification for early withdrawal penalty
        // $notification_data = [
        //     'account_id' => $_SESSION['account_id'] ?? DEFAULT_ADMIN_ID,
        //     'title' => 'Early Withdrawal Penalty',
        //     'message' => "A penalty of " . number_format($penalty_amount, 2) . " has been applied for early withdrawal from your " . 
        //                 $coop['data']['type_name'] . " account. Maturity date was " . $maturity_date . ".",
        //     'type' => 'warning'
        // ];
        // create_notification($notification_data);
    } else {
        // Add note for mature withdrawal
        $validated['data']['notes'] = 'Maturity withdrawal - account matured on ' . $maturity_date;
    } 
    
    // Continue with regular withdrawal logic 
    $validated['data']['transaction_type'] = WITHDRAWAL; 
    $transaction = update_balance($validated['data']); 
    
    // If early withdrawal, also record the penalty fee transaction 
    if (!$is_mature && isset($penalty_amount)) { 
        $penalty_data = [ 
            'member_id' => $member_id, 
            'caid' => $caid, 
            'amount' => $penalty_amount, 
            'transaction_type' => FEE, 
            'notes' => 'Early withdrawal penalty fee for ' . $coop['data']['type_name'] . ' account' 
        ]; 
        $penalty_transaction = update_balance($penalty_data); 
        
        // Add penalty information to the response
        if ($transaction['success']) {
            $transaction['data']['penalty_applied'] = true;
            $transaction['data']['penalty_amount'] = $penalty_amount;
            $transaction['data']['days_to_maturity'] = ceil((strtotime($maturity_date) - strtotime($current_date)) / (60 * 60 * 24));
            $transaction['message'] = 'Withdrawal processed with early withdrawal penalty';
        }
    } 
    
    // Add maturity information to the response
    if ($transaction['success']) {
        $transaction['data']['is_mature'] = $is_mature;
        $transaction['data']['maturity_date'] = $maturity_date;
        
        // Update account status if full withdrawal
        $member = get_member($member_id);
        if ($member['success'] && isset($member['data']['current_balance']) && 
            $member['data']['current_balance'] <= 0) {
            
            // Close the account if balance is zero after withdrawal
            update_membership_status($member_id, CLOSED);
            $transaction['data']['account_closed'] = true;
            $transaction['message'] = $is_mature ? 
                'Maturity withdrawal processed successfully. Account closed.' : 
                'Early withdrawal processed with penalty. Account closed.';
        }
    }
    
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

function handle_get_cooperative_accounts_by_criteria(mixed $payload): void
{
    //log_request('data:', $payload);
    $validated = validate_data($payload, [
        'membership_types' => 'required', //in:Savings Account,Time Deposit,Fixed Deposit,Special Savings,Youth Savings,Loan
        'status' => 'required' //in:active,inactive,suspended,closed
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

    $s_array = array_map('trim', explode(',', $validated['data']['status']));
    foreach ($s_array as $status) {
        if (!in_array($status, MEMBERSHIP_STATUS)) {
            return_response([
               'success' => false,
               'message' => "Invalid status value: {$status}",
               'status' => 400
            ]);
        }
    }

    $members = get_cooperative_accounts_by_criteria($mt_array, $s_array);
    return_response($members);
}

function handle_get_members_by_approval(mixed $payload): void
{
    $validated = validate_data($payload, [
       'approval' =>'required' //in:approved,pending
    ]);

    $as_array = array_map('trim', explode(',', $validated['data']['approval']));
    foreach ($as_array as $approval) {
        if (!in_array($approval, MEMBERSHIP_APPROVAL_STATUS)) {
            return_response([
              'success' => false,
              'message' => "Invalid approval status value: {$approval}",
              'status' => 400
            ]);
        }
    }

    $members = get_members_by_approval($as_array);
    return_response($members);
}

function handle_register_member(mixed $payload): void
{
    //log_request('payload', $payload);
    $validated = validate_data($payload, [
        //account information
        'profile_picture' => 'optional|image|mimes:jpeg,jpg,png|size:1048576',
        'role_id' => 'required|numeric|min:1|check:role_model',
        'email'   => 'required|email|check:is_email_unique',
        'username' => 'required|check:is_username_unique',
        'password' => 'required|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'required|match:password',
        //member
        'first_name' => 'required|string',
        'middle_name' => 'optional|string',
        'last_name' => 'required|string',
        'sex' => 'required|in:M,F',
        'contact_number' => 'required|length:13',
        'house_address' => 'required',
        'barangay' => 'required',
        'municipality' => 'required|string',
        'province' => 'required|string',
        'region' => 'required',
        //cooperative
        'selected_caids' =>'required',
        'page_from' => 'required',
        'recaptcha_response' => 'optional'
    ]);

     $page_from = $validated['data']['page_from'];
    if ($page_from === 'external') {
        $verified = verify_recaptcha($validated['data']['recaptcha_response'], get_client_ip());
        if(!$verified['success']) {
            return_response($verified);
        }
    }

    $upload_dir = __DIR__ . '/../../storage/uploads/profiles/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file = null;
    // Process the profile picture upload if one was provided
    if (isset($validated['data']['profile_picture']) && is_array($validated['data']['profile_picture'])) {
        $file = $validated['data']['profile_picture'];
    } else if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['name'])) {
        $file = $_FILES['profile_picture'];
    }

    // Only process the file if one was actually uploaded
    if ($file !== null && isset($file['name']) && !empty($file['name'])) {
        $filename = uniqid() . '_' . basename($file['name']);
        $upload_path = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $validated['data']['profile_picture'] = $filename;
        } else {
            return_response(['success' => false, 'message' => 'Failed to upload profile picture', 'status' => 500]);
        }
    } else {
        // No profile picture provided, set to null
        $validated['data']['profile_picture'] = null;
    }

    $created = register_member($validated['data']);
    if(!$created['success']) {
        return_response($created);
    }

    $response_message = 'Registered successfully. ';
    if($page_from === 'external') {
        $mailed = send_verification_email($validated['data']['email']);
        if (!$mailed['success']) {
            return_response($mailed);
        }
        $response_message .= $mailed['message'];
    }

    return_response(['success' => true, 'message' => $response_message]);

}

function handle_get_registered_member(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1',
    ]);
    $member = get_registered_member((int)$validated['data']['member_id']);
    return_response($member);
}

function handle_update_registered_member(mixed $payload): void
{
    //log_request('payload', $payload);
    $validated = validate_data($payload, [
        //account information
        'member_id' =>'required|numeric|min:1|check:member_model',
        'account_id' =>'required|numeric|min:1|check:account_model',
        'profile_picture' => 'optional|image|mimes:jpeg,jpg,png|size:1048576',
        'role_id' => 'required|numeric|min:1|check:role_model',
        'email'   => 'required|email|check_except:is_email_unique,account_id',
        'username' => 'required|check_except:is_username_unique,account_id',
        'password' => 'optional|min:8|contains:lowercase,uppercase,number,symbol',
        'confirm_password' => 'optional|match:password',
        //member
        'first_name' => 'required|string',
        'middle_name' => 'optional|string',
        'last_name' => 'required|string',
        'sex' => 'required|in:M,F',
        'contact_number' => 'required|length:13',
        'house_address' => 'required',
        'barangay' => 'required',
        'municipality' => 'required|string',
        'province' => 'required|string',
        'region' => 'required',
        //cooperative
        // 'selected_caids' =>'required',
        // 'page_from' => 'required'
        'account_changes' => 'required'
    ]);

    // Ensure account_changes is properly decoded as an array
    if (isset($validated['data']['account_changes']) && is_string($validated['data']['account_changes'])) {
        $validated['data']['account_changes'] = json_decode($validated['data']['account_changes'], true);
    }

    //check if there are image upload
    if(!empty($validated['data']['profile_picture'])) {
        $upload_dir = __DIR__. '/../../storage/uploads/profiles/';
        $file = null;
        // Process the profile picture upload
        if (isset($validated['data']['profile_picture']) && is_array($validated['data']['profile_picture'])) {
            $file = $validated['data']['profile_picture'];
        } else if (isset($_FILES['profile_picture']) &&!empty($_FILES['profile_picture']['name'])) {
            $file = $_FILES['profile_picture'];
        }

        $filename = uniqid() . '_' . basename($file['name']);
        $upload_path = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $validated['data']['profile_picture'] = $filename;
        } else {
            return_response(['success' => false, 'message' => 'Failed to upload profile picture', 'status' => 500]);
        }
    }

    $updated = update_registered_member($validated['data']);
    return_response($updated);
}

function handle_update_member_approval_status(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' =>'required|numeric|min:1|check:member_model',
        'approval' =>'required|string|in:approved,rejected,pending'
    ]);

    $member_id = (int)$validated['data']['member_id'];
    $approval = $validated['data']['approval'];

    $updated = update_member_approval_status($member_id, $approval);
    return_response($updated);
}

function handle_get_registered_members(mixed $payload): void
{
    $members = get_registered_members();
    return_response($members);
}

function handle_apply_services_to_member(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' =>'required|numeric|min:1|check:member_model',
        'selected_caids' =>'required'
    ]);

    $member_id = (int)$validated['data']['member_id'];
    # $data['selected_caids'] = '6,7'
    $type_id_array = array_map('trim', explode(',', $validated['data']['selected_caids']));
    
    $created = apply_services_to_member($member_id, $type_id_array);
    return_response($created);
}

function handle_get_transactions_by_cooperative(mixed $payload): void 
{
    $validated = validate_data($payload, [
        'member_id' =>'required|numeric|min:1|check:member_model',
        'type_name' => 'required|in:Savings Account,Time Deposit,Fixed Deposit,Special Savings,Youth Savings,Loan'
    ]);

    $member_id = (int)$validated['data']['member_id'];
    $type_name = $validated['data']['type_name'];

    $transactions = get_transactions_by_cooperative($member_id, $type_name);
    return_response($transactions);
}