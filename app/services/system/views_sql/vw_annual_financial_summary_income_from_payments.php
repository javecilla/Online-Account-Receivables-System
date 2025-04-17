<?php

declare(strict_types=1);

function vw_annual_financial_summary_income_from_payments(): string
{
  return "SELECT
      YEAR(payment_date) as year,
      SUM(amount) as total_payment_income
  FROM amortization_payments
  GROUP BY year
  ORDER BY year DESC";
}