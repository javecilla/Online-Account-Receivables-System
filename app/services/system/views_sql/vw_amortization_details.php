<?php

declare(strict_types=1);

function vw_amortization_details(): string
{
    /*
        # Amortization details with payment summary
        CREATE VIEW vw_amortization_details AS
    */
    return "WITH payment_summary AS (
        SELECT 
            ma.amortization_id,
            IFNULL(SUM(ap.amount), 0) as total_paid
        FROM member_amortizations ma
            LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
        GROUP BY ma.amortization_id
    )
    SELECT 
        ma.amortization_id,
        ma.member_id,
        ma.type_id,
        ma.principal_amount,
        ma.monthly_amount,
        ma.remaining_balance,
        ma.start_date,
        ma.end_date,
        ma.`status`,
        ma.approval,
        ma.created_at,
        ma.updated_at,
        `at`.type_name,
        `at`.`description`,
        `at`.interest_rate,
        ps.total_paid,
        -- (ma.remaining_balance - ps.total_paid) as balance_due,
         -- ma.remaining_balance as balance_due,
        m.current_balance,
        m.credit_balance,
        m.member_uid,
        CONCAT(m.first_name, ' ',IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as full_name,
         a.email
    FROM member_amortizations ma
        JOIN amortization_types `at` ON ma.type_id = `at`.type_id
        JOIN payment_summary ps ON ma.amortization_id = ps.amortization_id
        INNER JOIN members m ON ma.member_id = m.member_id
        LEFT JOIN accounts a ON m.account_id = a.account_id";
}
