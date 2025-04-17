<?php

declare(strict_types=1);

function vw_monthly_financial_summary_transactions(): string {
  return "SELECT
      DATE_FORMAT(created_at, '%Y-%m') as month_year,
      transaction_type,
      COUNT(*) as transaction_count,
      SUM(amount) as total_amount
  FROM member_transactions
  GROUP BY month_year, transaction_type
  ORDER BY month_year DESC, transaction_type";
}