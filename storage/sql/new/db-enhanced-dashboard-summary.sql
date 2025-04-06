USE oarsmc_db;
DROP VIEW IF EXISTS vw_enhanced_dashboard_summary;
CREATE VIEW vw_enhanced_dashboard_summary AS
SELECT -- Member Statistics
    (
        SELECT COUNT(*)
        FROM members
        WHERE membership_status = 'active'
    ) as total_active_members,
    (
        SELECT COUNT(*)
        FROM members
        WHERE membership_status = 'inactive'
    ) as total_inactive_members,
    ROUND(
        (
            SELECT COUNT(*)
            FROM members
            WHERE membership_status = 'active'
        ) * 100.0 / NULLIF(
            (
                SELECT COUNT(*)
                FROM members
            ),
            0
        ),
        2
    ) as member_activity_rate,
    -- Risk Metrics
    (
        SELECT COUNT(DISTINCT member_id)
        FROM member_amortizations
        WHERE status = 'defaulted'
    ) as total_defaulted_members,
    (
        SELECT ROUND(AVG(default_rate), 2)
        FROM vw_risk_assessment_dashboard
    ) as average_default_rate,
    (
        SELECT ROUND(AVG(percent_below_minimum), 2)
        FROM vw_risk_assessment_dashboard
    ) as avg_accounts_below_minimum_pct,
    -- Loan Performance
    (
        SELECT COUNT(*)
        FROM member_amortizations
        WHERE status = 'active'
    ) as active_loans_count,
    (
        SELECT SUM(remaining_balance)
        FROM member_amortizations
        WHERE status = 'active'
    ) as total_outstanding_loans,
    (
        SELECT ROUND(
                COUNT(
                    CASE
                        WHEN status = 'completed' THEN 1
                    END
                ) * 100.0 / NULLIF(COUNT(*), 0),
                2
            )
        FROM member_amortizations
    ) as loan_completion_rate,
    -- Financial Health
    (
        SELECT SUM(current_balance)
        FROM members
        WHERE membership_status = 'active'
    ) as total_active_balances,
    (
        SELECT ROUND(
                SUM(amount) * 100.0 / NULLIF(
                    (
                        SELECT SUM(monthly_amount)
                        FROM member_amortizations
                        WHERE status = 'active'
                    ),
                    0
                ),
                2
            )
        FROM amortization_payments
        WHERE MONTH(payment_date) = MONTH(CURRENT_DATE)
            AND YEAR(payment_date) = YEAR(CURRENT_DATE)
    ) as current_month_collection_efficiency,
    -- Growth Indicators
    (
        SELECT COUNT(*)
        FROM members
        WHERE MONTH(opened_date) = MONTH(CURRENT_DATE)
            AND YEAR(opened_date) = YEAR(CURRENT_DATE)
    ) as new_members_this_month,
    (
        SELECT COUNT(*) - LAG(COUNT(*)) OVER (
                ORDER BY DATE_FORMAT(opened_date, '%Y-%m')
            )
        FROM members
        WHERE DATE_FORMAT(opened_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
        GROUP BY DATE_FORMAT(opened_date, '%Y-%m')
    ) as member_growth_from_last_month;