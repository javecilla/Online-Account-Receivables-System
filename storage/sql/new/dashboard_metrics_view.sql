-- Drop existing view if it exists
DROP VIEW IF EXISTS vw_dashboard_metrics;
-- Create updated dashboard metrics view
CREATE VIEW vw_dashboard_metrics AS WITH active_members_summary AS (
    SELECT COUNT(*) as total_active_members,
        SUM(COALESCE(current_balance, 0)) as total_active_balances
    FROM members
    WHERE membership_status = 'active'
),
receivables_summary AS (
    SELECT SUM(COALESCE(remaining_balance, 0)) as total_receivables,
        SUM(
            CASE
                WHEN CURRENT_DATE > ma.end_date
                AND ma.status = 'active' THEN COALESCE(remaining_balance, 0)
                ELSE 0
            END
        ) as overdue_receivables,
        COUNT(
            DISTINCT CASE
                WHEN CURRENT_DATE > ma.end_date
                AND ma.status = 'active' THEN ma.member_id
            END
        ) as overdue_accounts,
        COUNT(DISTINCT ma.member_id) as total_borrowers
    FROM member_amortizations ma
    WHERE ma.status = 'active'
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
    CROSS JOIN receivables_summary rs;