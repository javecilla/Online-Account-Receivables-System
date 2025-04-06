<?php

declare(strict_types=1);

function vw_payment_collection_efficiency(): string
{
    /*
        # Payment Collection Efficiency
        CREATE VIEW vw_payment_collection_efficiency AS
    */
    return "SELECT at.type_name as loan_type,
        YEAR(ap.payment_date) as year,
        MONTH(ap.payment_date) as month,
        COUNT(DISTINCT ma.amortization_id) as active_loans,
        COUNT(ap.payment_id) as payments_made,
        SUM(ap.amount) as collected_amount,
        SUM(ma.monthly_amount) as expected_amount,
        ROUND(
            (
                SUM(ap.amount) * 100.0 / NULLIF(SUM(ma.monthly_amount), 0)
            ),
            2
        ) as collection_efficiency,
        AVG(DATEDIFF(ap.payment_date, ma.start_date)) as avg_days_to_pay
    FROM amortization_types at
        JOIN member_amortizations ma ON at.type_id = ma.type_id
        LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
    WHERE ma.status = 'active'
    GROUP BY at.type_name,
        YEAR(ap.payment_date),
        MONTH(ap.payment_date)
    ORDER BY year DESC,
        month DESC";
}
