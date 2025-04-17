<?php

declare(strict_types=1);

function vw_payment_trends_monthly(): string {
  return "SELECT
      DATE_FORMAT(ap.payment_date, '%Y-%m') as payment_month,
      COUNT(ap.payment_id) as total_payments,
      SUM(ap.amount) as total_amount_paid,
      AVG(ap.amount) as average_payment_amount
  FROM amortization_payments ap
  GROUP BY payment_month
  ORDER BY payment_month DESC";
}