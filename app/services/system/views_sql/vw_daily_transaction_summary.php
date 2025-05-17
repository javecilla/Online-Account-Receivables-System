<?php

declare(strict_types=1);

function vw_daily_transaction_summary(): string
{
    /*
        # Daily Transaction Summary View
        CREATE VIEW vw_daily_transaction_summary AS
    */
    return "SELECT 
            DATE(mt.created_at) as transaction_date,
            mt.transaction_type,
            COUNT(*) as transaction_count,
            SUM(mt.amount) as total_amount,
            MIN(mt.amount) as min_amount,
            MAX(mt.amount) as max_amount,
            AVG(mt.amount) as avg_amount,
            COUNT(DISTINCT ca.member_id) as unique_members
        FROM member_transactions mt
        INNER JOIN cooperative_accounts ca ON mt.cooperative_id = ca.caid
        LEFT JOIN members m ON ca.member_id = m.member_id
        LEFT JOIN member_types t ON ca.type_id = t.type_id";
}
