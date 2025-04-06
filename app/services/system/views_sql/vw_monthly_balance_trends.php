<?php

declare(strict_types=1);

function vw_monthly_balance_trends(): string
{
    /*
        # Monthly Account Balance Trends
        CREATE VIEW vw_monthly_balance_trends AS
    */
    return "SELECT DATE_FORMAT(mt.created_at, '%Y-%m') as month_year,
            mt.type_name as account_type,
            COUNT(DISTINCT m.member_id) as total_members,
            SUM(mt2.amount) as total_transactions,
            SUM(
                CASE
                    WHEN mt2.transaction_type IN ('deposit', 'interest') THEN mt2.amount
                    ELSE 0
                END
            ) as total_credits,
            SUM(
                CASE
                    WHEN mt2.transaction_type IN ('withdrawal', 'fee') THEN mt2.amount
                    ELSE 0
                END
            ) as total_debits,
            AVG(m.current_balance) as average_balance
        FROM member_types mt
            JOIN members m ON m.type_id = mt.type_id
            LEFT JOIN member_transactions mt2 ON m.member_id = mt2.member_id";
}
