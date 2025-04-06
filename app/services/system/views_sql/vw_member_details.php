<?php

declare(strict_types=1);

function vw_member_details(): string
{
    /*
        # Member details with account and type info
        CREATE VIEW vw_member_details AS
    */
    return "SELECT m.member_id,
        m.member_uid,
        m.first_name,
        m.middle_name,
        m.last_name,
        CONCAT(m.first_name, ' ',IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as full_name,
        m.contact_number,
        m.membership_status,
        m.current_balance,
        m.opened_date,
        m.closed_date,
        m.house_address,
        m.barangay,
        m.municipality,
        m.province,
        m.region,
        CONCAT(m.house_address, ', ', m.barangay, ', ', m.municipality, ', ', m.province, ', ', m.region) as full_address,
        mt.type_name as membership_type,
        mt.minimum_balance,
        mt.interest_rate,
        a.account_uid,
        a.email,
        a.account_status,
        a.created_at as registered_at
    FROM members m
        INNER JOIN member_types mt ON m.type_id = mt.type_id
        LEFT JOIN accounts a ON m.account_id = a.account_id";
}
