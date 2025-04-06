USE oarsmc_db;
DROP VIEW IF EXISTS vw_dashboard_metrics;
CREATE VIEW vw_dashboard_metrics AS WITH active_members_summary AS (
    SELECT COUNT(*) as total_active_members,
        SUM(current_balance) as total_active_balances
    FROM members
    WHERE membership_status = 'active'
),
receivables_summary AS (
    SELECT SUM(ma.remaining_balance) as total_receivables,
        SUM(
            CASE
                WHEN CURRENT_DATE > ma.end_date
                AND ma.status = 'active' THEN ma.remaining_balance
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
),
outstanding_summary AS (
    SELECT SUM(remaining_balance) as total_outstanding,
        SUM(
            CASE
                WHEN CURRENT_DATE > end_date THEN remaining_balance
                ELSE 0
            END
        ) as overdue_amount
    FROM member_amortizations
    WHERE status = 'active'
)
SELECT ams.total_active_members,
    ams.total_active_balances,
    rs.total_receivables,
    rs.overdue_receivables,
    os.total_outstanding,
    os.overdue_amount,
    ROUND(
        CASE
            WHEN os.total_outstanding > 0 THEN (os.overdue_amount / os.total_outstanding) * 100
            ELSE 0
        END,
        2
    ) as overdue_percentage,
    ROUND(
        CASE
            WHEN (rs.total_receivables + ams.total_active_balances) > 0 THEN (
                ams.total_active_balances / (rs.total_receivables + ams.total_active_balances)
            ) * 100
            ELSE 0
        END,
        2
    ) as equity_ratio,
    ROUND(
        CASE
            WHEN ams.total_active_balances > 0 THEN (rs.total_receivables / ams.total_active_balances) * 100
            ELSE 0
        END,
        2
    ) as debt_equity_ratio
FROM active_members_summary ams,
    receivables_summary rs,
    outstanding_summary os;