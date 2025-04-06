<?php

declare(strict_types=1);

function vw_monthly_receivables_trend(): string
{
    /*
        CREATE VIEW vw_monthly_receivables_trend AS
    */
    return "WITH monthly_metrics AS (
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
    ORDER BY month_date DESC";
}
