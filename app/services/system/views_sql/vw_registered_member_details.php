<?php

declare(strict_types=1);

function vw_registered_member_details(): string {
  return "SELECT
    a.account_id,
    a.profile_image,
    ar.role_id,
    ar.role_name,
    a.email,
    a.username,
    m.member_id,
    m.member_uid,
    m.first_name,
    m.middle_name,
    m.last_name,
    m.sex,
    m.contact_number,
    m.house_address,
    m.barangay,
    m.municipality,
    m.province,
    m.region,
    m.approval_status,
    m.current_balance,
    m.credit_balance,
    CONCAT(
        m.first_name, ' ',
        CASE 
            WHEN TRIM(m.middle_name) != '' THEN CONCAT(LEFT(TRIM(m.middle_name), 1), '. ')
            ELSE ''
        END,
        m.last_name
    ) AS full_name,
    CONCAT(m.house_address, ', ', m.barangay, ', ', m.municipality, ', ', m.province, ', ', m.region) AS full_address,
    m.created_at AS member_since,
    (
        SELECT JSON_ARRAYAGG(
            JSON_OBJECT(
                'caid', ca.caid,
                'type_id', ca.type_id,
                'type_name', mt.type_name,
                'minimum_balance', mt.minimum_balance,
                'interest_rate', mt.interest_rate,
                'penalty_rate', mt.penalty_rate,
                'minimum_penalty_fee', mt.minimum_penalty_fee,
                'maximum_penalty_fee', mt.maximum_penalty_fee,
                'penalty_rate', mt.penalty_rate,
                'opened_date', ca.opened_date,
                'closed_date', ca.closed_date,
                'status', ca.status
            )
        )
        FROM cooperative_accounts ca
        LEFT JOIN member_types mt ON ca.type_id = mt.type_id
        WHERE ca.member_id = m.member_id
    ) AS cooperative_accounts
  FROM members m
  INNER JOIN accounts a ON m.account_id = a.account_id
  INNER JOIN account_roles ar ON a.role_id = ar.role_id";
}