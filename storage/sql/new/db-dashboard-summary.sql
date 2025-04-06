USE oarsmc_db;
DROP VIEW IF EXISTS vw_dashboard_summary;
CREATE VIEW vw_dashboard_summary AS WITH current_month AS (
    SELECT IFNULL(SUM(total_balance), 0) as total_balance,
        IFNULL(
            SUM(
                CASE
                    WHEN type_name = 'Savings' THEN total_balance
                    ELSE 0
                END
            ),
            0
        ) as total_savings,
        IFNULL(
            (
                SELECT SUM(total_credits)
                FROM vw_monthly_balance_trends
                WHERE month_year = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
            ),
            0
        ) as total_revenue
    FROM vw_active_accounts_summary
),
previous_month AS (
    SELECT IFNULL(SUM(total_balance), 0) as prev_total_balance,
        IFNULL(
            SUM(
                CASE
                    WHEN type_name = 'Savings' THEN total_balance
                    ELSE 0
                END
            ),
            0
        ) as prev_total_savings,
        IFNULL(
            (
                SELECT SUM(total_credits)
                FROM vw_monthly_balance_trends
                WHERE month_year = DATE_FORMAT(
                        DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH),
                        '%Y-%m'
                    )
            ),
            0
        ) as prev_total_revenue
    FROM vw_active_accounts_summary
)
SELECT cm.total_balance,
    cm.total_savings,
    cm.total_revenue,
    CASE
        WHEN pm.prev_total_balance = 0 THEN 0
        ELSE ROUND(
            (
                (cm.total_balance - pm.prev_total_balance) / pm.prev_total_balance * 100
            ),
            2
        )
    END as balance_change_percentage,
    CASE
        WHEN pm.prev_total_savings = 0 THEN 0
        ELSE ROUND(
            (
                (cm.total_savings - pm.prev_total_savings) / pm.prev_total_savings * 100
            ),
            2
        )
    END as savings_change_percentage,
    CASE
        WHEN pm.prev_total_revenue = 0 THEN 0
        ELSE ROUND(
            (
                (cm.total_revenue - pm.prev_total_revenue) / pm.prev_total_revenue * 100
            ),
            2
        )
    END as revenue_change_percentage
FROM current_month cm
    CROSS JOIN previous_month pm;