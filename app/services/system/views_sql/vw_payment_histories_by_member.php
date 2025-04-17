<?php

declare(strict_types=1);

function vw_payment_histories_by_member(): string {
  return "SELECT
      m.member_id,
      m.first_name,
      m.last_name,
      CONCAT(m.first_name, ' ', IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as full_name,
      ap.payment_id,
      ap.amount,
      ap.payment_date,
      ap.reference_number,
      ap.payment_method,
      ap.created_at
  FROM amortization_payments ap
  JOIN member_amortizations ma ON ap.amortization_id = ma.amortization_id
  JOIN members m ON ma.member_id = m.member_id
  ORDER BY m.member_id, ap.payment_date DESC";
}