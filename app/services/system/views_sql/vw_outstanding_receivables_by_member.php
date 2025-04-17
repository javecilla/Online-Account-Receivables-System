<?php

declare(strict_types=1);

function vw_outstanding_receivables_by_member(): string
{
  return "SELECT
      m.member_id,
      m.first_name,
      m.last_name,
      CONCAT(m.first_name, ' ', IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''), m.last_name) as full_name,
      SUM(ma.remaining_balance) as total_outstanding_receivable
  FROM members m
  JOIN member_amortizations ma ON m.member_id = ma.member_id
  WHERE ma.status IN ('pending', 'overdue') AND ma.approval = 'approved'
  GROUP BY m.member_id, m.first_name, m.last_name
  ORDER BY total_outstanding_receivable DESC";
}