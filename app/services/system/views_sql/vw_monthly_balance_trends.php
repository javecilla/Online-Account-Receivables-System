<?php

declare(strict_types=1);

function vw_monthly_balance_trends(): string
{
    /*
        # Monthly Account Balance Trends
        CREATE VIEW vw_monthly_balance_trends AS
    */
    return "SELECT DATE_FORMAT(mtr.created_at, '%Y-%m') as month_year,
            mty.type_name as account_type,
            COUNT(DISTINCT mem.member_id) as total_members,
            SUM(mtr.amount) as total_transactions,
            SUM(
                CASE
                    WHEN mtr.transaction_type IN ('deposit', 'interest', 'credit') THEN mtr.amount
                    ELSE 0
                END
            ) as total_credits,
            SUM(
                CASE
                    WHEN mtr.transaction_type IN ('withdrawal', 'fee', 'credit_used', 'loan_payment') THEN mtr.amount
                    ELSE 0
                END
            ) as total_debits,
            AVG(mem.current_balance) as average_balance
        FROM member_transactions mtr
            JOIN cooperative_accounts ca ON mtr.cooperative_id = ca.caid
            JOIN members mem ON ca.member_id = mem.member_id
            JOIN member_types mty ON ca.type_id = mty.type_id
        GROUP BY month_year, account_type";
}
