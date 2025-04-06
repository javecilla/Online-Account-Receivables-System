<?php

declare(strict_types=1);

function vw_membership_status_summary(): string
{
    /*
        # Membership status summary
        CREATE VIEW vw_membership_status_summary AS
    */
    return "SELECT mt.type_name,
        m.membership_status,
        COUNT(m.member_id) as total_members,
        SUM(m.current_balance) as total_balance
    FROM member_types mt
        LEFT JOIN members m ON mt.type_id = m.type_id
    GROUP BY mt.type_name, m.membership_status
    ORDER BY mt.type_name, m.membership_status";
}
