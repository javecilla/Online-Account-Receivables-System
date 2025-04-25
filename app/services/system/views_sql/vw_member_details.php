<?php

declare(strict_types=1);

require_once 'vw_member_loan_metrics.php';

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
        m.credit_balance,
        m.opened_date,
        m.closed_date,
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
        IFNULL(lm.total_loans, 0) as total_loans,
        IFNULL(lm.paid_loans, 0) as paid_loans,
        IFNULL(lm.pending_loans, 0) as pending_loans,
        IFNULL(lm.overdue_loans, 0) as overdue_loans,
        IFNULL(lm.defaulted_loans, 0) as defaulted_loans,
        IFNULL(lm.overdue_rate, 0) as overdue_rate,
        IFNULL(lm.default_rate, 0) as default_rate,
        IFNULL(lm.risk_level, 'No History') as risk_level,
        m.created_at as member_created_at,
        m.updated_at as member_updated_at
    FROM members m
        INNER JOIN member_types mt ON m.type_id = mt.type_id
        LEFT JOIN accounts a ON m.account_id = a.account_id
        LEFT JOIN account_roles ar ON a.role_id= ar.role_id
        LEFT JOIN (" . vw_member_loan_metrics() . ") lm ON m.member_id = lm.member_id";
}
