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
        mt.created_at AS transaction_created_at,
        ca.caid,
        m.member_id,
        m.member_uid,
        CONCAT(m.first_name, ' ', IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) AS member_full_name,
        CONCAT(m.house_address, ', ', m.barangay, ', ', m.municipality, ', ', m.province, ', ', m.region) AS full_address,
        t.type_id,
        t.type_name,
        COALESCE(
            CONCAT(m2.first_name, ' ', IFNULL(CONCAT(LEFT(m2.middle_name, 1), '. '), ''), m2.last_name),
            CONCAT(e.first_name, ' ', IFNULL(CONCAT(LEFT(e.middle_name, 1), '. '), ''), e.last_name),
            'Unknown'
        ) AS processed_by
    FROM member_transactions mt
    INNER JOIN cooperative_accounts ca ON mt.cooperative_id = ca.caid
    LEFT JOIN members m ON ca.member_id = m.member_id
    LEFT JOIN member_types t ON ca.type_id = t.type_id
    INNER JOIN accounts a ON mt.created_by = a.account_id
    LEFT JOIN members m2 ON a.account_id = m2.account_id
    LEFT JOIN employees e ON a.account_id = e.account_id";
}
