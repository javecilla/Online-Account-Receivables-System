<?php

declare(strict_types=1);

function vw_annual_financial_summary_transactions(): string
{
  return "SELECT
      YEAR(created_at) as year,
      transaction_type,
      COUNT(*) as transaction_count,
      SUM(amount) as total_amount
  FROM member_transactions
  GROUP BY year, transaction_type
  ORDER BY year DESC, transaction_type";
}