<?php

declare(strict_types=1);

function vw_employee_details(): string
{
    /*
        # Employee details with account info
        CREATE VIEW vw_employee_details AS
    */
    return "SELECT 
        e.employee_id,
        e.first_name,
        e.middle_name,
        e.last_name,
        CONCAT(e.first_name, ' ', IFNULL(CONCAT(LEFT(e.middle_name, 1), '. '), ''), e.last_name) as full_name,
        e.contact_number,
        e.salary,
        e.rata,
        a.account_id,
        a.account_uid,
        a.username,
        a.email,
        a.email_verified_at,
        a.account_status,
        a.created_at as account_created_at,
        a.updated_at as account_updated_at,
        ar.role_name,
        ar.role_id,
        e.created_at as employee_created_at,
        e.updated_at as employee_updated_at
    FROM employees e
        LEFT JOIN accounts a ON e.account_id = a.account_id
        LEFT JOIN account_roles ar ON a.role_id = ar.role_id";
}
