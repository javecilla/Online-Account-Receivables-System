<?php

declare(strict_types=1);

function vw_daily_transaction_summary(): string
{
    /*
        # Daily Transaction Summary View
        CREATE VIEW vw_daily_transaction_summary AS
    */
    return "SELECT DATE(created_at) as transaction_date,
            transaction_type,
            COUNT(*) as transaction_count,
            SUM(amount) as total_amount,
            MIN(amount) as min_amount,
            MAX(amount) as max_amount,
            AVG(amount) as avg_amount
        FROM member_transactions";
}
