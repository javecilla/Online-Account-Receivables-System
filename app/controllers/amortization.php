<?php

declare(strict_types=1);

/**
 * TODO: Amortization Management Functions
 */

function handle_create_amortization(mixed $payload): void
{
    $validated = validate_data($payload, [
        'member_id' => 'required|numeric|min:1|check:member_model',
        'type_id' => 'required|numeric|min:1|check:amortization_type_model',
        'amount' => 'required|numeric|min:1000',
        'term_months' => 'required|numeric|min:1',
        'start_date' => 'required|date:YYYY-MM-DD'
    ]);

    //check if this member has existing active amortizations
    $active_check = check_active_amortizations($validated['data']['member_id']);
    if (!$active_check['success']) {
        return_response($active_check);
    }

    if ($active_check['has_active']) {
        return_response([
            'success' => false,
            'message' => "Member already has {$active_check['active_count']} active amortization(s). Please complete or update existing amortizations first.",
            'status' => 400
        ]);
    }

    $amortization = create_amortization($validated['data']);
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
    //paylaod: status=active,completed,defaulted
    //log_request('data:', $payload);
    $validated = validate_data($payload, [
        'status' => 'required' //in:active,completed,defaulted
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


function handle_process_amortization_payment(mixed $payload): void
{
    //log_request('data:', $payload);
    $validated = validate_data($payload, [
        'amortization_id' => 'required|numeric|min:1|check:amortization_model',
        'payment_method' => 'required|in:cash,check,bank_transfer,online_payment,others',
        'amount' => 'required|numeric|min:1',
        'payment_date' => 'required|date:YYYY-MM-DD',
        'notes' => 'optional'
    ]);
    //return_response(['success' => true, 'message' => 'test']);

    $payment = process_amortization_payment($validated['data']);
    return_response($payment);
}

function handle_update_amortization_status(mixed $payload): void
{
    $validated = validate_data($payload, [
        'amortization_id' => 'required|numeric|min:1|check:amortization_model',
        'status' => 'required|string|in:active,completed,defaulted',
    ]);

    $status_update = update_amortization_status(
        (int)$validated['data']['amortization_id'],
        $validated['data']['status']
    );
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
        $status_update = update_amortization_status(
            (int)$validated['data']['amortization_id'],
            AMORTIZATION_ACTIVE
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
