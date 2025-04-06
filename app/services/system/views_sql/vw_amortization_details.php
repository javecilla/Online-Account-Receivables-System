<?php

declare(strict_types=1);

function vw_amortization_details(): string
{
    /*
        # Amortization details with payment summary
        CREATE VIEW vw_amortization_details AS
    */
    return "SELECT 
        ma.amortization_id,
        ma.member_id,
        ma.type_id,
        ma.principal_amount,
        ma.monthly_amount,
        ma.remaining_balance,
        ma.start_date,
        ma.end_date,
        ma.`status`,
        ma.created_at,
        `at`.type_name,
        `at`.`description`,
        `at`.interest_rate,
        IFNULL(SUM(ap.amount), 0) as total_paid,
        (ma.remaining_balance - IFNULL(SUM(ap.amount), 0)) as balance_due,
        m.current_balance,
        CONCAT(m.first_name, ' ',IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as full_name
    FROM member_amortizations ma
        JOIN amortization_types `at` ON ma.type_id = `at`.type_id
        LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
        INNER JOIN members m ON ma.member_id = m.member_id
    GROUP BY ma.amortization_id,
        ma.member_id,
        ma.type_id,
        ma.principal_amount,
        ma.monthly_amount,
        ma.remaining_balance,
        ma.start_date,
        ma.end_date,
        ma.`status`,
        ma.created_at,
        `at`.type_name,
        `at`.`description`,
        `at`.interest_rate";
}
