<?php

declare(strict_types=1);

require_once __DIR__ . '/../helpers/api.php';
require_once __DIR__ . '/../services/app.php';
require_once __DIR__ . '/../controllers/app.php';

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 86400');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

try {
    if (get_request_method() === 'POST') {
        $request = get_server_request_data();
        //log_request('request: ', $request);
        initialized_api_middleware($request);

        $action = $request['action'];
        //log_request('action: ', $action);
        $payload = $request['data'];
        //log_request('payload: ', $payload);


        switch ($action) {
            /* ACCOUNT */
            case 'create_account':
                handle_create_account($payload);
                break;

            case 'verify_account':
                handle_verify_account($payload);
                break;

            case 'request_account_verification':
                handle_request_account_verification($payload);
                break;

            case 'request_otp':
                handle_request_otp($payload);
                break;

            case 'verify_otp':
                handle_verify_otp($payload);
                break;

            case 'request_password_reset':
                handle_request_password_reset($payload);
                break;

            case 'verify_reset_code':
                handle_verify_reset_code($payload);
                break;

            case 'login_account':
                handle_login_account($payload);
                break;

            case 'logout_account':
                handle_logout_account($payload);
                break;

            /* MEMBER */
            case 'create_member':
                handle_create_member($payload);
                break;

            //TODO:
            case 'create_member_cooperative':
                handle_create_member_cooperative($payload);
                break;

            //TODO:
            case 'create_employee_cooperative':
                handle_create_employee_cooperative($payload);
                break;

            /* //TODO: POST ALTERNATIVES FOR PUT/PATCH ENDPOINTS */
            case 'update_account':
                handle_update_account($payload);
                break;

            case 'reset_password':
                handle_reset_password($payload);
                break;

            case 'update_member':
                handle_update_member($payload);
                break;

            case 'update_member_status':
                handle_update_member_status($payload);
                break;

            case 'update_member_cooperative':
                handle_update_member_cooperative($payload);
                break;

            case 'update_employee_cooperative':
                handle_update_employee_cooperative($payload);
                break;

            case 'update_amortization_status':
                handle_update_amortization_status($payload);
                break;

            case 'update_amortization_approval':
                handle_update_amortization_approval($payload);
                break;

            case 'update_amortization':
                handle_update_amortization($payload);
                break;

            /* TRANSACTIONS */
            case 'deposit':
                handle_deposit_transaction($payload);
                break;

            case 'withdraw':
                handle_withdrawal_transaction($payload);
                break;

            case 'credit_interest':
                handle_credit_interest($payload);
                break;

            case 'check_penalties':
                handle_check_and_apply_penalties($payload);
                break;

            //TODO:  AMORTIZATION
            case 'create_amortization':
                handle_create_amortization($payload);
                break;

            case 'process_amortization_payment':
                handle_process_amortization_payment($payload);
                break;

            /* //TODO: POST ALTERNATIVES FOR DELETE ENDPOINTS */
            case 'delete_account':
                handle_delete_account($payload);
                break;

            case 'delete_member':
                handle_delete_member($payload);
                break;

            case 'delete_amortization':
                handle_delete_amortization($payload);
                break;

            // TODO: Notification
            case 'create_notification':
                handle_create_notification($payload);
                break;

            case 'update_account_status':
                handle_update_account_status($payload);
                break;

            case 'create_contact_messages':
                handle_create_contact_messages($payload);
                break;

            case 'update_contact_map':
                handle_update_contact_map($payload);
                break;

            default:
                throw new Exception('Bad Request! Invalid action.', 400);
                break;
        }
    } else if (get_request_method() === 'GET') {
        $request = get_query_params();
        //log_request('request: ', $request);
        initialized_api_middleware($request);

        $action = $request['action'];
        unset($request['action']);
        $payload = [...$request];
        //log_request('payload: ', $payload);

        switch ($action) {
            case 'get_accounts':
                handle_get_accounts($payload);
                break;

            case 'get_account':
                handle_get_account($payload);
                break;

            case 'get_account_roles':
                handle_get_account_roles($payload);
                break;

            case 'get_members':
                handle_get_members($payload);
                break;

            case 'get_member':
                handle_get_member($payload);
                break;

            case 'get_membership_types':
                handle_get_membership_types($payload);
                break;

            case 'get_member_amortizations':
                handle_get_member_amortizations($payload);
                break;
            
            // Member risk assessment endpoints
            case 'get_high_risk_members':
                handle_get_high_risk_members($payload);
                break;
            
            case 'get_member_risk_dashboard':
                handle_get_member_risk_dashboard();
                break;
            
            case 'assess_account_closure':
                handle_assess_account_closure($payload);
                break;
            
            case 'generate_default_notifications':
                handle_generate_default_notifications($payload);
                break;

            case 'get_employee_by_account':
                handle_get_employee_by_account($payload);
                break;

            case 'get_member_by_account':
                handle_get_member_by_account($payload);
                break;

            case 'get_amortization_payments':
                handle_get_amortization_payments($payload);
                break;

            case 'get_member_amortization_payments':
                handle_get_member_amortization_payments($payload);
                break;

            // Analytics endpoints
            case 'get_daily_transaction_stats':
                handle_get_daily_transaction_stats($payload);
                break;

            case 'get_monthly_balance_trends':
                handle_get_monthly_balance_trends($payload);
                break;

            case 'get_loan_performance_metrics':
                handle_get_loan_performance_metrics($payload);
                break;

            case 'get_payment_collection_efficiency':
                handle_get_payment_collection_efficiency($payload);
                break;

            case 'get_member_growth_stats':
                handle_get_member_growth_stats($payload);
                break;

            case 'get_risk_assessment_metrics':
                handle_get_risk_assessment_metrics($payload);
                break;

            case 'get_amortization_summary_stats':
                handle_get_amortization_summary_stats($payload);
                break;

            case 'get_dashboard_metrics':
                handle_get_dashboard_metrics($payload);
                break;

            case 'get_monthly_receivables_trend':
                handle_get_monthly_receivables_trend($payload);
                break;

            case 'get_monthly_overdue_metrics':
                handle_get_monthly_overdue_metrics($payload);
                break;

            //

            case 'get_annual_financial_summary_income_from_payments':
                handle_get_annual_financial_summary_income_from_payments($payload);
                break;

            case 'get_annual_financial_summary_transactions':
                handle_get_annual_financial_summary_transactions($payload);
                break;

            case 'get_monthly_financial_summary_income_from_payments':
                handle_get_monthly_financial_summary_income_from_payments($payload);
                break;

            case 'get_monthly_financial_summary_transactions':
                handle_get_monthly_financial_summary_transactions($payload);
                break;

            case 'get_outstanding_receivables_by_member':
                handle_get_outstanding_receivables_by_member($payload);
                break;

            case 'get_payment_histories_by_member':
                handle_get_payment_histories_by_member($payload);
                break;

            case 'get_payment_trends_monthly':
                handle_get_payment_trends_monthly($payload);
                break;

            case 'get_quarterly_financial_summary_income_from_payments':
                handle_get_quarterly_financial_summary_income_from_payments($payload);
                break;

            case 'get_quarterly_financial_summary_transactions':
                handle_get_quarterly_financial_summary_transactions($payload);
                break;

            //
            case 'get_members_transactions':
                handle_get_members_transactions($payload);
                break;

            case 'get_member_transactions':
                handle_get_member_transactions($payload);
                break;

            case 'get_amortizations_by_status':
                handle_get_amortizations_by_status($payload);
                break;
            
            case 'get_amortizations_by_criteria':
                handle_get_amortizations_by_criteria($payload);
                break;

            case 'get_amortizations_by_approval':
                handle_get_amortizations_by_approval($payload);
                break;

            case 'get_member_approved_amortizations':
                handle_get_member_approved_amortizations($payload);
                break;

            case 'get_member_request_amortizations':
                handle_get_member_request_amortizations($payload);
                break;

            case 'get_amortization_types':
                handle_get_amortization_types($payload);
                break;
            
            case 'get_amortization_type':
                handle_get_amortization_type($payload);
                break;

            case 'get_amortization':
                handle_get_amortization($payload);
                break;

            case 'get_members_by_criteria':
                handle_get_members_by_criteria($payload);
                break;

            case 'get_member_account_balance_metrics':
                handle_get_member_account_balance_metrics($payload);
                break;

            case 'get_member_savings_goal_metrics':
                handle_get_member_savings_goal_metrics($payload);
                break;

            case 'get_member_active_loans_metrics':
                handle_get_member_active_loans_metrics($payload);
                break;

            case 'get_member_account_status_metrics':
                handle_get_member_account_status_metrics($payload);
                break;

            case 'get_member_upcoming_payments':
                handle_get_member_upcoming_payments($payload);
                break;

            case 'get_accounts_by_criteria':
                handle_get_accounts_by_criteria($payload);
                break;

            case 'get_contact_map':
                handle_get_contact_map($payload);
                break;

             case 'get_aboutus_content':
                handle_get_aboutus_content($payload);
                break;

            default:
                throw new Exception('Bad Request! Invalid action.', 400);
                break;
        }
    } else if (get_request_method() === 'PUT' || get_request_method() === 'PATCH') {
        $request = get_server_request_data();
        //log_request('request: ', $request);
        initialized_api_middleware($request);

        $action = $request['action'];
        //log_request('action: ', $action);
        $payload = $request['data'];
        //log_request('payload: ', $payload);

        switch ($action) {
            case 'update_account':
                handle_update_account($payload);
                break;

            case 'reset_password':
                handle_reset_password($payload);
                break;

            case 'update_member':
                handle_update_member($payload);
                break;

            case 'update_member_status':
                handle_update_member_status($payload);
                break;

            case 'update_member_cooperative':
                handle_update_member_cooperative($payload);
                break;

            case 'update_employee_cooperative':
                handle_update_employee_cooperative($payload);
                break;

            case 'update_amortization_status':
                handle_update_amortization_status($payload);
                break;

            case 'update_amortization_approval':
                handle_update_amortization_approval($payload);
                break;

            case 'update_amortization':
                handle_update_amortization($payload);
                break;

            case 'update_account_status':
                handle_update_account_status($payload);
                break;

            default:
                throw new Exception('Bad Request! Invalid action.', 400);
                break;
        }
    } else if (get_request_method() === 'DELETE') {
        $request = get_server_request_data();
        //log_request('request: ', $request);
        initialized_api_middleware($request);

        $action = $request['action'];
        //log_request('action: ', $action);
        $payload = $request['data'];
        //log_request('payload: ', $payload);

        switch ($action) {
            case 'delete_account':
                handle_delete_account($payload);
                break;

            case 'delete_member':
                handle_delete_member($payload);
                break;

            default:
                throw new Exception('Bad Request! Invalid action.', 400);
                break;
        }
    }
} catch (Throwable $e) {
    log_error("An error occurred: {$e->getTraceAsString()}");
    return_response([
        'success' => false,
        'message' => "An error occurred: {$e->getMessage()}",
        'status' => 500
    ]);
}
