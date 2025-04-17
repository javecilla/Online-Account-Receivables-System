<?php

declare(strict_types=1);

function vw_monthly_financial_summary_income_from_payments(): string
{
  return "SELECT
      DATE_FORMAT(payment_date, '%Y-%m') as month_year,
      SUM(amount) as total_payment_income
  FROM amortization_payments
  GROUP BY month_year
  ORDER BY month_year DESC";
}