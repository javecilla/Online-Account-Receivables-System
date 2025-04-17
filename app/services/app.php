<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../config/logger.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

begin_session();

require_once __DIR__ . '/../constants/system.php';
require_once __DIR__ . '/../helpers/system.php';
require_once __DIR__ . '/../helpers/global.php';

require_once __DIR__ . '/vendors/mail.php';
require_once __DIR__ . '/vendors/recaptcha.php';
require_once __DIR__ . '/vendors/geoip.php';

require_once __DIR__ . '/system/views_sql/vw_account_details.php';
require_once __DIR__ . '/system/views_sql/vw_active_accounts_summary.php';
require_once __DIR__ . '/system/views_sql/vw_amortization_details.php';
require_once __DIR__ . '/system/views_sql/vw_amortization_payment_summary.php';
require_once __DIR__ . '/system/views_sql/vw_daily_transaction_summary.php';
require_once __DIR__ . '/system/views_sql/vw_dashboard_metrics.php';
require_once __DIR__ . '/system/views_sql/vw_dashboard_summary.php';
require_once __DIR__ . '/system/views_sql/vw_employee_details.php';
require_once __DIR__ . '/system/views_sql/vw_loan_performance_analytics.php';
require_once __DIR__ . '/system/views_sql/vw_member_details.php';
require_once __DIR__ . '/system/views_sql/vw_member_growth_analysis.php';
require_once __DIR__ . '/system/views_sql/vw_member_locations.php';
require_once __DIR__ . '/system/views_sql/vw_member_transaction_history.php';
require_once __DIR__ . '/system/views_sql/vw_membership_status_summary.php';
require_once __DIR__ . '/system/views_sql/vw_monthly_balance_trends.php';
require_once __DIR__ . '/system/views_sql/vw_monthly_overdue_metrics.php';
require_once __DIR__ . '/system/views_sql/vw_monthly_receivables_trend.php';
require_once __DIR__ . '/system/views_sql/vw_payment_collection_efficiency.php';
require_once __DIR__ . '/system/views_sql/vw_risk_assessment_dashboard.php';
require_once __DIR__ . '/system/views_sql/vw_amortization_payments_details.php';
//
require_once __DIR__ . '/system/views_sql/vw_annual_financial_summary_income_from_payments.php';
require_once __DIR__ . '/system/views_sql/vw_annual_financial_summary_transactions.php';
require_once __DIR__ . '/system/views_sql/vw_monthly_financial_summary_income_from_payments.php';
require_once __DIR__ . '/system/views_sql/vw_monthly_financial_summary_transactions.php';
require_once __DIR__ . '/system/views_sql/vw_outstanding_receivables_by_member.php';
require_once __DIR__ . '/system/views_sql/vw_payment_histories_by_member.php';
require_once __DIR__ . '/system/views_sql/vw_payment_trends_monthly.php';
require_once __DIR__ . '/system/views_sql/vw_quarterly_financial_summary_income_from_payments.php';
require_once __DIR__ . '/system/views_sql/vw_quarterly_financial_summary_transactions.php';
// require_once __DIR__ . '/system/views_sql/test.php';

require_once __DIR__ . '/system/account_role.php';
require_once __DIR__ . '/system/account.php';
require_once __DIR__ . '/system/employee.php';
require_once __DIR__ . '/system/member_type.php';
require_once __DIR__ . '/system/member.php';
// TODO:
require_once __DIR__ . '/system/amortization_type.php';
require_once __DIR__ . '/system/amortization.php';
