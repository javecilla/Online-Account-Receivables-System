<?php

declare(strict_types=1);

function vw_member_growth_analysis(): string
{
    /*
        # Member Growth Analysis
        CREATE VIEW vw_member_growth_analysis AS
    */
    return "SELECT DATE_FORMAT(opened_date, '%Y-%m') as month_year,
        COUNT(*) as new_members,
        SUM(
            CASE
                WHEN membership_status = 'active' THEN 1
                ELSE 0
            END
        ) as active_members,
        SUM(
            CASE
                WHEN membership_status = 'inactive' THEN 1
                ELSE 0
            END
        ) as inactive_members,
        SUM(
            CASE
                WHEN membership_status = 'suspended' THEN 1
                ELSE 0
            END
        ) as suspended_members,
        SUM(
            CASE
                WHEN membership_status = 'closed' THEN 1
                ELSE 0
            END
        ) as closed_members,
        AVG(current_balance) as avg_opening_balance
    FROM members
    GROUP BY DATE_FORMAT(opened_date, '%Y-%m')
    ORDER BY month_year DESC";
}
