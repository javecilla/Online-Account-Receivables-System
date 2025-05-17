<?php

declare(strict_types=1);

function vw_cooperative_accounts_details(): string {
  return "SELECT  ca.caid,
    ca.opened_date,
    ca.closed_date,
    ca.status,
    m.member_id,
    m.member_uid,
    m.first_name,
    m.middle_name,
    m.last_name,
    CONCAT(m.first_name, ' ',IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as full_name,
    m.contact_number,
    m.approval_status,
    m.current_balance,
    m.credit_balance,
    m.house_address,
    m.barangay,
    m.municipality,
    m.province,
    m.region,
    CONCAT(m.house_address, ', ', m.barangay, ', ', m.municipality, ', ', m.province, ', ', m.region) as full_address,
    mt.type_name as membership_type,
    mt.type_id as membership_id,
    mt.minimum_balance,
    mt.interest_rate,
    a.account_id,
    a.account_uid,
    a.username,
    a.email,
    a.email_verified_at,
    a.account_status,
    a.created_at as account_created_at,
    a.updated_at as account_updated_at,
    ar.role_id,
    ar.role_name,
    m.created_at as member_created_at,
    m.updated_at as member_updated_at
  FROM cooperative_accounts ca
    INNER JOIN members m ON ca.member_id = m.member_id
    INNER JOIN member_types mt ON ca.type_id = mt.type_id
    LEFT JOIN accounts a ON m.account_id = a.account_id
    LEFT JOIN account_roles ar ON a.role_id= ar.role_id";
}