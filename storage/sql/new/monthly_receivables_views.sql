-- Drop existing views if they exist
DROP VIEW IF EXISTS vw_monthly_receivables_trend;
DROP VIEW IF EXISTS vw_monthly_overdue_metrics;
-- Create view for monthly receivables trend
CREATE VIEW vw_monthly_receivables_trend AS WITH monthly_metrics AS (
    SELECT DATE_FORMAT(ma.start_date, '%Y-%m-01') as month_date,
        SUM(ma.principal_amount) as total_principal,
        SUM(ma.remaining_balance) as total_remaining,
        SUM(COALESCE(ap.amount, 0)) as total_collected,
        COUNT(DISTINCT ma.amortization_id) as total_loans,
        COUNT(
            DISTINCT CASE
                WHEN ma.status = 'active'
                AND CURRENT_DATE > ma.end_date THEN ma.amortization_id
            END
        ) as overdue_loans
    FROM member_amortizations ma
        LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
        AND DATE_FORMAT(ap.payment_date, '%Y-%m') = DATE_FORMAT(ma.start_date, '%Y-%m')
    GROUP BY DATE_FORMAT(ma.start_date, '%Y-%m-01')
)
SELECT month_date,
    total_principal,
    total_remaining,
    total_collected,
    total_loans,
    overdue_loans,
    ROUND(
        (total_collected / NULLIF(total_principal, 0)) * 100,
        2
    ) as collection_rate,
    ROUND(
        (overdue_loans / NULLIF(total_loans, 0)) * 100,
        2
    ) as overdue_rate
FROM monthly_metrics
ORDER BY month_date DESC;
-- Create view for monthly average days overdue
CREATE VIEW vw_monthly_overdue_metrics AS WITH payment_delays AS (
    SELECT DATE_FORMAT(ma.start_date, '%Y-%m-01') as month_date,
        ma.amortization_id,
        CASE
            WHEN ma.status = 'completed' THEN 0
            WHEN ma.status = 'active'
            AND CURRENT_DATE > ma.end_date THEN DATEDIFF(CURRENT_DATE, ma.end_date)
            ELSE 0
        END as days_overdue,
        CASE
            WHEN ap.payment_date IS NOT NULL THEN DATEDIFF(
                ap.payment_date,
                DATE_ADD(ma.start_date, INTERVAL 1 MONTH)
            )
            ELSE 0
        END as payment_delay
    FROM member_amortizations ma
        LEFT JOIN (
            SELECT amortization_id,
                MIN(payment_date) as payment_date
            FROM amortization_payments
            GROUP BY amortization_id
        ) ap ON ma.amortization_id = ap.amortization_id
)
SELECT month_date,
    COUNT(amortization_id) as total_loans,
    ROUND(AVG(NULLIF(days_overdue, 0)), 2) as avg_days_overdue,
    ROUND(AVG(NULLIF(payment_delay, 0)), 2) as avg_payment_delay,
    COUNT(
        CASE
            WHEN days_overdue > 0 THEN 1
        END
    ) as overdue_count,
    COUNT(
        CASE
            WHEN payment_delay > 0 THEN 1
        END
    ) as delayed_payment_count
FROM payment_delays
GROUP BY month_date
ORDER BY month_date DESC;