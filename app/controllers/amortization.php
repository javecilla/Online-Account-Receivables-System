<?php

declare(strict_types=1);

/**
 * TODO: Amortization Management Functions
 */

function handle_create_amortization(mixed $payload): void
{
    // log_request('data:', $payload);
    // return_response(['success' => true,'message' => 'test']);
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'type_id' => 'required|numeric|min:1|check:amortization_type_model',
        //'amount' => 'required|numeric|min:1000',
        'principal_amount' => 'required|numeric|min:1',
        'monthly_amount' => 'required|numeric|min:1',
        'remaining_balance' => 'required|numeric|min:1', //total repayment amount
        'term_months' => 'required|numeric|min:1', //beta not sure pa
        'start_date' => 'required|date:YYYY-MM-DD',
        'end_date' => 'required|date:YYYY-MM-DD',
        'purpose' => 'optional|string|in:internal,external'
    ]);

    layer_two_amortization_validation($validated);

    // log_request('data:', $validated['data']);
    // return_response(['success' => true,'message' => 'test']);
    $amortization = create_amortization($validated['data']);
    //TODO: send email notification to admin that a new amortization request has been created
    //TODO: send email notification to member that their amortization request has been created
    return_response($amortization);
}

function layer_two_amortization_validation(mixed $validated): void {
    $type = get_amortization_type((int)$validated['data']['type_id']);
    if(!$type['success']) {
        return_response($type);
    }
    $amortization_type = $type['data'];

    $minTermMonths = 3;
    $maxTermMonths = $amortization_type['term_months'];

    if($validated['data']['term_months'] < $minTermMonths || $validated['data']['term_months'] > $maxTermMonths) {
        return_response([
           'success' => false,
           'message' => "Term months for {$amortization_type['type_name']} must be between {$minTermMonths} and {$maxTermMonths}",
           'status' => 400
        ]);
    }

    $minAmount = $amortization_type['minimum_amount'];
    $maxAmount = $amortization_type['maximum_amount'];

    if($validated['data']['principal_amount'] < $minAmount || $validated['data']['principal_amount'] > $maxAmount) {
        return_response([
          'success' => false,
          'message' => "Principal amount for {$amortization_type['type_name']} must be between {$minAmount} and {$maxAmount}",
          'status' => 400
        ]);
    }
    
    //check if this member has 3 existing active amortizations
    $active_check = check_active_amortizations((int)$validated['data']['member_id']);
    if (!$active_check['success']) {
        return_response($active_check);
    }

    //if ($active_check['has_active']) {
    if ($active_check['active_count'] >= 3) {
        return_response([
            'success' => false,
            //Member already has {$active_check['active_count']} active amortization(s). Please complete or update existing amortizations first.
            'message' => "You have {$active_check['active_count']} active amortization(s). Please complete or update existing amortizations first.",
            'status' => 400
        ]);
    }
}

function handle_update_amortization(mixed $payload): void
{
    $validated = validate_data($payload, [
        'amortization_id' =>'required|numeric|min:1|check:amortization_model',
        'member_id' => 'required|numeric|min:1|check:member_model',
        'type_id' => 'required|numeric|min:1|check:amortization_type_model',
        'principal_amount' => 'required|numeric|min:1',
        'monthly_amount' => 'required|numeric|min:1',
        'remaining_balance' => 'required|numeric|min:1',
        'term_months' => 'required|numeric|min:1',
        'start_date' => 'required|date:YYYY-MM-DD',
        'end_date' => 'required|date:YYYY-MM-DD'
    ]);

    layer_two_amortization_validation($validated);

    // log_request('data:', $validated['data']);
    // return_response(['success' => true,'message' => 'test']);

    $updated = update_amortization((int)$validated['data']['amortization_id'], $validated['data']);
    return_response($updated);
}

function handle_get_amortization(mixed $payload): void
{
    $validated = validate_data($payload, [
        'amortization_id' =>'required|numeric|min:1|check:amortization_model'
    ]);

    $amortization = get_amortization((int)$validated['data']['amortization_id']);
    return_response($amortization);
}

function handle_get_member_amortizations(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'page' => 'optional|numeric|min:1',
        'per_page' => 'optional|numeric|min:1',
        'status' => 'optional|in:active,completed,defaulted'
    ]);

    $page = isset($validated['data']['page']) ? (int)$validated['data']['page'] : 1;
    $per_page = isset($validated['data']['per_page']) ? (int)$validated['data']['per_page'] : 10;
    $status = $validated['data']['status'] ?? null;

    $amortizations = get_member_amortizations(
        (int)$validated['data']['member_id'],
        $page,
        $per_page,
        $status
    );
    return_response($amortizations);
}

function handle_get_amortizations_by_status(mixed $payload): void
{
    //log_request('data:', $payload);
    $validated = validate_data($payload, [
        'status' => 'required' //in:paid,pending,overdue,defaulted
    ]);

    $status_array = array_map('trim', explode(',', $validated['data']['status']));
    foreach ($status_array as $status) {
        if (!in_array($status, AMORTIZATION_STATUS)) {
            return_response([
                'success' => false,
                'message' => "Invalid status value: {$status}",
                'status' => 400
            ]);
        }
    }

    $amortizations = get_amortizations_by_status($status_array);
    return_response($amortizations);
}

function handle_get_amortizations_by_criteria(mixed $payload): void
{
    //log_request('data:', $payload);
    $validated = validate_data($payload, [
        'status' => 'required', //in:paid,pending,overdue,defaulted
        'loan_types' => 'required' //in:Educational Loan,Calamity Loan,Business Loan,Personal Loan,Agricultural Loan
    ]);

    $status_array = array_map('trim', explode(',', $validated['data']['status']));
    foreach ($status_array as $status) {
        if (!in_array($status, AMORTIZATION_STATUS)) {
            return_response([
                'success' => false,
                'message' => "Invalid status value: {$status}",
                'status' => 400
            ]);
        }
    }

    $type_names_array = array_map('trim', explode(',', $validated['data']['loan_types']));
    foreach ($type_names_array as $type_name) {
        if (!in_array($type_name, LOAN_TYPE_NAMES)) {
            return_response([
                'success' => false,
                'message' => "Invalid amortization type name provided: {$type_name}",
                'status' => 400
            ]);
        }
    }

    $amortizations = get_amortizations_by_criteria($status_array, $type_names_array);
    return_response($amortizations);
}

function handle_get_member_approved_amortizations(mixed $payload): void
{
    log_request('data:', $payload); 
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'status' => 'required', //in:active,completed,defaulted,
    ]);

    $status_array = array_map('trim', explode(',', $validated['data']['status']));
    foreach ($status_array as $status) {
        if (!in_array($status, AMORTIZATION_STATUS)) {
            return_response([
                'success' => false,
                'message' => "Invalid status value: {$status}",
                'status' => 400
            ]);
        }
    }

    $amortizations = get_member_approved_amortizations(
        (int)$validated['data']['member_id'],
        $status_array
    );
    return_response($amortizations);
}

function handle_get_amortizations_by_approval(mixed $payload): void
{
    //paylaod: status=pending,approved,rejected
    //log_request('data:', $payload);
    $validated = validate_data($payload, [
        'approval' => 'required' //in:pending,approved,rejected
    ]);

    $approval_array = array_map('trim', explode(',', $validated['data']['approval']));
    foreach ($approval_array as $approval) {
        if (!in_array($approval, AMORTIZATION_APPROVAL_STATUS)) {
            return_response([
                'success' => false,
                'message' => "Invalid approval value: {$approval}",
                'status' => 400
            ]);
        }
    }

    $amortizations = get_amortizations_by_approval($approval_array);
    return_response($amortizations);
}

function handle_get_member_request_amortizations(mixed $payload): void
{
    log_request('data:', $payload);
    $validated = validate_data($payload, [
        'member_id' =>'required|numeric|min:1|check:member_model',
        'approval' => 'required'
    ]);

    $approval_array = array_map('trim', explode(',', $validated['data']['approval']));
    foreach ($approval_array as $approval) {
        if (!in_array($approval, AMORTIZATION_APPROVAL_STATUS)) {
            return_response([
                'success' => false,
                'message' => "Invalid approval value: {$approval}",
                'status' => 400
            ]);
        }
    }

    $amortizations = get_member_request_amortizations((int)$validated['data']['member_id'], $approval_array);
    return_response($amortizations);
}

// TODO:
function handle_process_amortization_payment(mixed $payload): void
{
    // log_request('data:', $payload); // Keep for debugging if needed
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'amortization_id' => 'required|numeric|min:1|check:amortization_model',
        'used_credit_balance' => 'required|numeric|min:0', // Can be 0
        'original_amount_payment' => 'required|numeric|min:0', // Can be 0 if only credit is used
        'final_amount_payment' =>'required|numeric|min:1',
        'payment_method' =>'required|in:cash,check,bank_transfer,online_payment,others',
        'payment_date' => 'required|date:YYYY-MM-DD',
        'notes' => 'required|string|min:1|max:500',
        'is_use_credit' => 'required|boolean',
        'is_create_credit' => 'required|boolean'
    ]);

    //check and Ensure final_amount matches sum if credit is used
    if ($validated['data']['is_use_credit']) {
        $calculated_final = (float)$validated['data']['original_amount_payment'] + (float)$validated['data']['used_credit_balance'];
        if (abs($calculated_final - (float)$validated['data']['final_amount_payment']) > 0.01) { // Allow for small float inaccuracies
            return_response([
                'success' => false,
                'message' => 'Payment amount mismatch. Final amount does not equal original payment plus used credit.',
                'status' => 400
            ]);
        }
    } else {
        //if not using credit, original and final should match, and used credit should be 0
         if ((float)$validated['data']['used_credit_balance'] !== 0.0) {
             return_response([
                'success' => false,
                'message' => 'Payment amount mismatch. Credit balance should be zero when not using credit.',
                'status' => 400
            ]);
         }
         if (abs((float)$validated['data']['original_amount_payment'] - (float)$validated['data']['final_amount_payment']) > 0.01) {
             return_response([
                'success' => false,
                'message' => 'Payment amount mismatch. Final amount does not equal original payment when not using credit.',
                'status' => 400
            ]);
         }
    }

    // Ensure at least one payment method (original or credit) is non-zero
    if ((float)$validated['data']['original_amount_payment'] <= 0 && (float)$validated['data']['used_credit_balance'] <= 0) {
         return_response([
            'success' => false,
            'message' => 'Invalid payment amount. Both original payment and used credit cannot be zero or less.',
            'status' => 400
        ]);
    }

    // log_request('data:', $validated['data']);
    // return_response(['success' => true,'message' => 'test']);

    $processed = process_amortization_payment($validated['data']);
    return_response($processed);
}

function handle_update_amortization_status(mixed $payload): void
{
    $validated = validate_data($payload, [
        'amortization_id' => 'required|numeric|min:1|check:amortization_model',
        'status' => 'required|string|in:paid,pending,overdue,defaulted',
        // added for admin side (optional, used for notification context if available)
        'account_id' => 'optional|numeric|min:1|check:account_model',
        'email' => 'optional|email|check:is_email_exist',
        'member_id' => 'optional|numeric|min:1|check:member_model',
        'member_name' => 'optional',
        'title' => 'optional',
        'message' => 'optional'
    ]);

    // log_request('data:', $validated['data']);
    // return_response(['success' => true,'message' => 'test']);

    $amortization_id = (int)$validated['data']['amortization_id'];
    $new_status = $validated['data']['status'];

    if($validated['data']['status'] === AMORTIZATION_DEFAULTED) {
        $amort = get_amortization($amortization_id);
        if(!$amort['success']) {
            return_response($amort);
        }

        $amortization = $amort['data'];
        $member_id = (int)$validated['data']['member_id'];
    
        $mem = get_member($member_id);
        if(!$mem['success']) {
            return_response($mem);
        }

        $member = $mem['data'];
        $notification_payload = [
            // Use account_id from payload if admin initiated, otherwise maybe null or a system ID
            'account_id' => $member['account_id'],
            'title' => 'Loan Default Notice',
            'message' => "We regret to inform you that your loan (Amortization ID: {$amortization_id}, Type: {$amortization['type_name']}) has been marked as defaulted due to non-payment or other reasons as per our terms.",
            'type' => 'system_alert',
        ];

        $notification_created = create_notification($notification_payload);
        if(!$notification_created['success']) {
            return_response($notification_created);
        }

        //send email notification to member
        $recipient_name = htmlspecialchars($validated['data']['member_name']);
        $recipient_email = $validated['data']['email']; //$validated['data']['email']
        $notification_title = htmlspecialchars($validated['data']['title']);
        $notification_message = nl2br(htmlspecialchars($validated['data']['message']));

        $email_subject = "OARSMC Avecilla - {$notification_title}";

        $email_body_content = <<<HTML
            <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                <p>Dear {$recipient_name},</p>
                <p>We regret to inform you that your loan (Amortization ID: {$amortization_id}, Type: {$amortization['type_name']}) has been marked as defaulted due to non-payment or other reasons as per our terms.</p>
                <p><strong>{$notification_title}</strong></p>
                <p>{$notification_message}</p>
                <p>If you believe you received this email in error, please disregard it or contact us immediately.</p>
            </div>
        HTML;

        $mailed = send_email($recipient_email, $email_subject, $email_body_content);
        if (!$mailed['success']) {
            return_response(['success' => false, 'message' => 'Failed to send email: ' . $mailed['message'], 'status' => 500]);
        }
    }

    $status_update = update_amortization_status($amortization_id, $new_status);
    return_response($status_update);
}

function handle_update_amortization_approval(mixed $payload): void
{
    $validated = validate_data($payload, [
        'amortization_id' => 'required|numeric|min:1|check:amortization_model',
        'approval' => 'required|string|in:pending,approved,rejected',
    ]);

    //if approved, update status to active
    if ($validated['data']['approval'] === AMORTIZATION_APPROVED) {
        $amort = get_amortization((int)$validated['data']['amortization_id']);
        if (!$amort['success']) {
            return_response($amort);
        }

        //check if this member has 3 existing active amortizations
        $active_check = check_active_amortizations((int)$amort['data']['member_id']);
        if (!$active_check['success']) {
            return_response($active_check);
        }
        //if ($active_check['has_active']) {
        if ($active_check['active_count'] >= 3) {
            return_response([
                'success' => false,
                'message' => "Cannot approved this amortization because, A member {$amort['data']['full_name']} has already {$active_check['active_count']} active amortization(s).",
                'status' => 400
            ]);
        }

        $status_update = update_amortization_status(
            (int)$validated['data']['amortization_id'],
            AMORTIZATION_PENDING
            //AMORTIZATION_ACTIVE
        );
        if (!$status_update['success']) {
            return_response($status_update);
        }
    }
    //TODO: if rejected, send email notification to user that their request for loan is not approved (update status to defaulted)

    $approval_update = update_amortization_approval(
        (int)$validated['data']['amortization_id'],
        $validated['data']['approval']
    );
    return_response($approval_update);
}

function handle_delete_amortization(mixed $payload): void
{
    $validated = validate_data($payload, [
        'amortization_id' => 'required|numeric|min:1|check:amortization_model'
    ]);

    $deleted = delete_amortization((int)$validated['data']['amortization_id']);
    return_response($deleted);
}

function handle_get_amortization_payments(mixed $payload): void
{
    $payments = get_amortization_payments();
    return_response($payments);
}

function handle_get_member_amortization_payments(mixed $payload): void
{
    $validated = validate_data($payload, [
        'amortization_id' => 'required|numeric|min:1|check:amortization_model'
    ]);

    $payments = get_member_amortization_payments((int)$validated['data']['amortization_id']);
    return_response($payments);
}



/**
 * Analytics and Reporting Handlers
 */

function handle_get_daily_transaction_stats(mixed $payload): void
{
    $validated = validate_data($payload, [
        'start_date' => 'optional|date:Y-m-d',
        'end_date' => 'optional|date:Y-m-d'
    ]);

    $stats = get_daily_transaction_stats(
        $validated['data']['start_date'] ?? null,
        $validated['data']['end_date'] ?? null
    );
    return_response($stats);
}

function handle_get_monthly_balance_trends(mixed $payload): void
{
    $validated = validate_data($payload, [
        'year' => 'optional|numeric|min:2024|max:2099'
    ]);

    $trends = get_monthly_balance_trends($validated['data']['year'] ?? null);
    return_response($trends);
}

function handle_get_loan_performance_metrics(mixed $payload): void
{
    $metrics = get_loan_performance_metrics();
    return_response($metrics);
}

function handle_get_payment_collection_efficiency(mixed $payload): void
{
    $validated = validate_data($payload, [
        'year' => 'optional|numeric|min:2024|max:2099',
        'month' => 'optional|numeric|min:1|max:12'
    ]);

    $efficiency = get_payment_collection_efficiency(
        (int)$validated['data']['year'] ?? null,
        (int)$validated['data']['month'] ?? null
    );
    return_response($efficiency);
}

function handle_get_member_growth_stats(mixed $payload): void
{
    $validated = validate_data($payload, [
        'start_date' => 'optional|date:Y-m-d',
        'end_date' => 'optional|date:Y-m-d'
    ]);

    $stats = get_member_growth_stats(
        $validated['data']['start_date'] ?? null,
        $validated['data']['end_date'] ?? null
    );
    return_response($stats);
}

function handle_get_risk_assessment_metrics(mixed $payload): void
{
    $metrics = get_risk_assessment_metrics();
    return_response($metrics);
}

function handle_get_amortization_summary_stats(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'optional|numeric|min:1|check:member_model'
    ]);

    $stats = get_amortization_summary_stats((int)$validated['data']['member_id'] ?? null);
    return_response($stats);
}

function handle_get_dashboard_metrics(mixed $payload): void
{
    $summary = get_dashboard_metrics();
    return_response($summary);
}

function handle_get_monthly_receivables_trend(mixed $payload): void
{
    $validated = validate_data($payload, [
        'start_date' => 'optional|date:Y-m-d',
        'end_date' => 'optional|date:Y-m-d'
    ]);

    $trends = get_monthly_receivables_trend(
        $validated['data']['start_date'] ?? null,
        $validated['data']['end_date'] ?? null
    );
    return_response($trends);
}

function handle_get_monthly_overdue_metrics(mixed $payload): void
{
    $validated = validate_data($payload, [
        'start_date' => 'optional|date:Y-m-d',
        'end_date' => 'optional|date:Y-m-d'
    ]);

    $metrics = get_monthly_overdue_metrics(
        $validated['data']['start_date'] ?? null,
        $validated['data']['end_date'] ?? null
    );
    return_response($metrics);
}

function handle_get_amortization_types(mixed $payload): void
{
    $types = get_amortization_types();
    return_response($types);
}

function handle_get_amortization_type(mixed $payload): void
{
    $validated = validate_data($payload, [
        'type_id' =>'required|numeric|min:1|check:amortization_type_model'
    ]);

    $types = get_amortization_type((int)$validated['data']['type_id']);
    return_response($types);
}

function handle_get_annual_financial_summary_income_from_payments(mixed $payload): void
{
    $data = get_annual_financial_summary_income_from_payments();
    return_response($data);
}

function handle_get_annual_financial_summary_transactions(mixed $payload): void
{
    $data = get_annual_financial_summary_transactions();
    return_response($data);
}

function handle_get_monthly_financial_summary_income_from_payments(mixed $payload): void
{
    $data = get_monthly_financial_summary_income_from_payments();
    return_response($data);
}

function handle_get_monthly_financial_summary_transactions(mixed $payload): void
{
    $data = get_monthly_financial_summary_transactions();
    return_response($data);
}

function handle_get_outstanding_receivables_by_member(mixed $payload): void
{
    $data = get_outstanding_receivables_by_member();
    return_response($data);
}

function handle_get_payment_histories_by_member(mixed $payload): void
{
    $data = get_payment_histories_by_member();
    return_response($data);
}

function handle_get_payment_trends_monthly(mixed $payload): void
{
    $data = get_payment_trends_monthly();
    return_response($data);
}

function handle_get_quarterly_financial_summary_income_from_payments(mixed $payload): void
{
    $data = get_quarterly_financial_summary_income_from_payments();
    return_response($data);
}

function handle_get_quarterly_financial_summary_transactions(mixed $payload): void
{
    $data = get_quarterly_financial_summary_transactions();
    return_response($data);
}