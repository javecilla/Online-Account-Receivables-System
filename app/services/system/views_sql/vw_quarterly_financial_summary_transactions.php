<?php

declare(strict_types=1);

function vw_quarterly_financial_summary_transactions(): string {
  return "SELECT
      CONCAT(YEAR(created_at), '-Q', QUARTER(created_at)) as quarter_year,
      transaction_type,
      COUNT(*) as transaction_count,
      SUM(amount) as total_amount
  FROM member_transactions
  GROUP BY quarter_year, transaction_type
  ORDER BY quarter_year DESC, transaction_type";
}