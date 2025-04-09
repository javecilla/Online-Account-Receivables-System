<?php

declare(strict_types=1);

function vw_member_transaction_history(): string
{
    return "SELECT 
        mt.transaction_id,
        mt.transaction_type,
        mt.amount,
        mt.previous_balance,
        mt.new_balance,
        mt.reference_number,
        mt.notes,
        mt.created_at as transaction_created_at,
        m.member_id,
        m.member_uid,
        CONCAT(m.first_name, ' ',IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as member_full_name,
        CONCAT(m.house_address, ', ', m.barangay, ', ', m.municipality, ', ', m.province, ', ', m.region) as full_address
    FROM member_transactions mt
    INNER JOIN members m ON mt.member_id = m.member_id
    INNER JOIN accounts a ON mt.created_by = a.account_id
    ";
}
