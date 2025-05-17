<?php

declare(strict_types=1);

function vw_dashboard_metrics(): string
{
    /*
        CREATE VIEW vw_dashboard_metrics AS
    */
    return "WITH active_members_summary AS (
        SELECT COUNT(DISTINCT m.member_id) as total_active_members,
            SUM(COALESCE(m.current_balance, 0)) as total_active_balances
        FROM members m
        JOIN cooperative_accounts ca ON m.member_id = ca.member_id
        WHERE m.approval_status = 'approved' AND ca.status = 'active'
    ),
    receivables_summary AS (
        SELECT SUM(COALESCE(ma.remaining_balance, 0)) as total_receivables,
            SUM(
                CASE
                    WHEN ma.status = 'overdue' OR (CURRENT_DATE > ma.end_date AND ma.status = 'pending') 
                    THEN COALESCE(ma.remaining_balance, 0)
                    ELSE 0
                END
            ) as overdue_receivables,
            COUNT(
                DISTINCT CASE
                    WHEN ma.status = 'overdue' OR (CURRENT_DATE > ma.end_date AND ma.status = 'pending')
                    THEN ma.member_id
                END
            ) as overdue_accounts,
            COUNT(DISTINCT ma.member_id) as total_borrowers
        FROM member_amortizations ma
        WHERE ma.status IN ('pending', 'overdue')
    )
    SELECT
        -- First card: Member balances and count
        ams.total_active_balances,
        ams.total_active_members,
        
        -- Second card: Receivables and borrowers
        rs.total_receivables,
        rs.total_borrowers,
        
        -- Third card: Overdue metrics
        rs.overdue_receivables,
        rs.overdue_accounts,
        ROUND(
            CASE
                WHEN rs.total_receivables > 0 THEN (rs.overdue_receivables / rs.total_receivables) * 100
                ELSE 0
            END,
            2
        ) as overdue_percentage
    FROM active_members_summary ams
        CROSS JOIN receivables_summary rs";
}
