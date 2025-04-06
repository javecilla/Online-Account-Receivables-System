<?php

declare(strict_types=1);

function vw_loan_performance_analytics(): string
{
    /*
        # Loan Performance Analytics
        CREATE VIEW vw_loan_performance_analytics AS
    */
    return "SELECT at.type_name as loan_type,
        COUNT(ma.amortization_id) as total_loans,
        SUM(ma.principal_amount) as total_principal,
        SUM(ma.remaining_balance) as total_remaining,
        AVG(ma.monthly_amount) as avg_monthly_payment,
        COUNT(
            CASE
                WHEN ma.status = 'completed' THEN 1
            END
        ) as completed_loans,
        COUNT(
            CASE
                WHEN ma.status = 'defaulted' THEN 1
            END
        ) as defaulted_loans,
        COUNT(
            CASE
                WHEN ma.status = 'active' THEN 1
            END
        ) as active_loans,
        ROUND(
            (
                COUNT(
                    CASE
                        WHEN ma.status = 'defaulted' THEN 1
                    END
                ) * 100.0 / COUNT(*)
            ),
            2
        ) as default_rate,
        AVG(
            DATEDIFF(
                IFNULL(
                    (
                        SELECT MAX(payment_date)
                        FROM amortization_payments
                        WHERE amortization_id = ma.amortization_id
                    ),
                    CURRENT_DATE
                ),
                ma.start_date
            )
        ) as avg_loan_duration_days
    FROM amortization_types at
        LEFT JOIN member_amortizations ma ON at.type_id = ma.type_id
    GROUP BY at.type_name";
}
