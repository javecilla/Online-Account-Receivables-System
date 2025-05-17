<?php

declare(strict_types=1);

function vw_risk_assessment_dashboard(): string
{
    /*
        # Risk Assessment Dashboard
        CREATE VIEW vw_risk_assessment_dashboard AS
    */
    return "SELECT mt.type_name as account_type,
        COUNT(DISTINCT ca.member_id) as total_accounts,
        SUM(
            CASE
                WHEN m.current_balance < mt.minimum_balance THEN 1
                ELSE 0
            END
        ) as accounts_below_minimum,
        ROUND(
            (
                SUM(
                    CASE
                        WHEN m.current_balance < mt.minimum_balance THEN 1
                        ELSE 0
                    END
                ) * 100.0 / COUNT(*)
            ),
            2
        ) as percent_below_minimum,
        COUNT(
            DISTINCT CASE
                WHEN ma.status = 'defaulted' THEN m.member_id
            END
        ) as members_with_defaults,
        ROUND(
            (
                COUNT(
                    DISTINCT CASE
                        WHEN ma.status = 'defaulted' THEN m.member_id
                    END
                ) * 100.0 / COUNT(DISTINCT ca.member_id)
            ),
            2
        ) as default_rate,
        AVG(m.current_balance) as avg_balance,
        MIN(m.current_balance) as min_balance,
        MAX(m.current_balance) as max_balance
    FROM member_types mt
        JOIN cooperative_accounts ca ON mt.type_id = ca.type_id
        JOIN members m ON ca.member_id = m.member_id
        LEFT JOIN member_amortizations ma ON m.member_id = ma.member_id
    WHERE ca.status = 'active'
    GROUP BY mt.type_name";
}
