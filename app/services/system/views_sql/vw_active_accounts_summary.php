<?php

declare(strict_types=1);

function vw_active_accounts_summary(): string
{
    /*
        # Active accounts summary by type
        CREATE VIEW vw_active_accounts_summary AS
    */
    return "SELECT mt.type_name,
        COUNT(m.member_id) as total_members,
        SUM(m.current_balance) as total_balance,
        MIN(m.current_balance) as min_balance,
        MAX(m.current_balance) as max_balance,
        AVG(m.current_balance) as avg_balance
    FROM member_types mt
        LEFT JOIN members m ON mt.type_id = m.type_id AND m.membership_status = 'active'
    GROUP BY mt.type_id, mt.type_name";
}
