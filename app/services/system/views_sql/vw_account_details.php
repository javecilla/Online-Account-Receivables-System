<?php

declare(strict_types=1);

function vw_account_details(): string
{
    /*
        # Account details with roles view
        CREATE VIEW vw_account_details AS
    */
    return "SELECT 
        a.account_id,
        a.account_uid,
        a.email,
        a.username,
        a.account_status,
        a.email_verified_at,
        a.first_login_at,
        ar.role_name,
        a.created_at,
        a.updated_at
    FROM accounts a
        INNER JOIN account_roles ar ON a.role_id = ar.role_id";
}
