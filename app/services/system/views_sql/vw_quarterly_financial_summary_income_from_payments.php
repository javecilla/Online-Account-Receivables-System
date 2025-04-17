<?php

declare(strict_types=1);

function vw_quarterly_financial_summary_income_from_payments(): string {
  return "SELECT
      CONCAT(YEAR(payment_date), '-Q', QUARTER(payment_date)) as quarter_year,
      SUM(amount) as total_payment_income
  FROM amortization_payments
  GROUP BY quarter_year
  ORDER BY quarter_year DESC";
}