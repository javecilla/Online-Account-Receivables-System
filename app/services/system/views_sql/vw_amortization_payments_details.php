<?php

declare(strict_types=1);

function vw_amortization_payments_details(): string
{
    return "SELECT ap.payment_id, 
        ap.payment_method,
        ap.amount,
        ap.payment_date,
        ap.reference_number,
        ap.notes,
        ap.created_at as payment_created_at,
            ma.`status`,
            CONCAT(m.first_name, ' ',IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as payment_made_by,
            COALESCE(
                CONCAT(emp.first_name, ' ', IFNULL(CONCAT(LEFT(emp.middle_name, 1), '. '), ''), emp.last_name),
                CONCAT(mem.first_name, ' ', IFNULL(CONCAT(LEFT(mem.middle_name, 1), '. '), ''), mem.last_name)
            ) AS processed_by
    FROM amortization_payments ap
    INNER JOIN member_amortizations ma ON ap.amortization_id = ma.amortization_id
    LEFT JOIN members m ON ma.member_id = m.member_id
    LEFT JOIN accounts acc ON ap.created_by = acc.account_id
    LEFT JOIN employees emp ON acc.account_id = emp.account_id
    LEFT JOIN members mem ON acc.account_id = mem.account_id";
}
