<?php

declare(strict_types=1);

function vw_monthly_overdue_metrics(): string
{
    /*
        CREATE VIEW vw_monthly_overdue_metrics AS
    */
    return "WITH payment_delays AS (
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
    ORDER BY month_date DESC";
}
