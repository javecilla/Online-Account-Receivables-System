<?php

declare(strict_types=1);

function vw_amortization_payment_summary(): string
{
    /*
        # Payment Summary View for each member's amortization
        CREATE VIEW vw_amortization_payment_summary AS
    */
    return "SELECT m.member_id,
        m.member_uid,
        CONCAT(m.first_name,' ', IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as full_name,
        ma.amortization_id,
        `at`.type_name,
        ma.principal_amount,
        ma.monthly_amount,
        ma.remaining_balance,
        COUNT(ap.payment_id) as total_payments,
        IFNULL(SUM(ap.amount), 0) as total_amount_paid,
        ROUND(ma.principal_amount + (
            ma.principal_amount * (`at`.interest_rate / 100) * (
                TIMESTAMPDIFF(MONTH, ma.start_date, ma.end_date) / 12
            )
            ), 2
        ) as total_payable,
        (
            SELECT MIN(payment_date)
            FROM amortization_payments
            WHERE amortization_id = ma.amortization_id
        ) as first_payment_date,
        (
            SELECT MAX(payment_date)
            FROM amortization_payments
            WHERE amortization_id = ma.amortization_id
        ) as last_payment_date,
        ma.start_date,
        ma.end_date,
        ma.`status`,
        CASE
            WHEN ma.`status` = 'completed' THEN 'Completed'
            WHEN CURRENT_DATE > ma.end_date AND ma.`status` = 'active' THEN 'Overdue'
            WHEN ma.`status` = 'defaulted' THEN 'Defaulted'
            ELSE 'Active'
        END as payment_status
    FROM members m
        INNER JOIN member_amortizations ma ON m.member_id = ma.member_id
        INNER JOIN amortization_types `at` ON ma.type_id = `at`.type_id
        LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
    GROUP BY m.member_id,
        m.member_uid,
        m.first_name,
        m.middle_name,
        m.last_name,
        ma.amortization_id,
        `at`.type_name,
        ma.principal_amount,
        ma.monthly_amount,
        ma.remaining_balance,
        ma.start_date,
        ma.end_date,
        ma.`status`";
}
