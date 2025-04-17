-- Daily Transaction Summary
SELECT DATE(created_at) as transaction_date,
	transaction_type,
	COUNT(*) as transaction_count,
	SUM(amount) as total_amount,
	MIN(amount) as min_amount,
	MAX(amount) as max_amount,
	AVG(amount) as avg_amount
FROM member_transactions
WHERE DATE(created_at) BETWEEN '2025-03-31' AND '2025-04-17'
GROUP BY DATE(created_at), transaction_type
ORDER BY transaction_date DESC

-- Outstanding receivables by member
SELECT
    m.member_id,
    m.first_name,
    m.last_name,
    SUM(ma.remaining_balance) as total_outstanding_receivable
FROM members m
JOIN member_amortizations ma ON m.member_id = ma.member_id
WHERE ma.status IN ('pending', 'overdue') AND ma.approval = 'approved'
GROUP BY m.member_id, m.first_name, m.last_name
ORDER BY total_outstanding_receivable DESC;

-- Outstanding receivables total
SELECT
    SUM(ma.remaining_balance) as grand_total_outstanding_receivable
FROM member_amortizations ma
WHERE ma.status IN ('pending', 'overdue') AND ma.approval = 'approved';

-- Payment histories by member
SELECT
    m.member_id,
    m.first_name,
    m.last_name,
    ap.payment_id,
    ap.amount,
    ap.payment_date,
    ap.reference_number,
    ap.payment_method,
    ap.created_at
FROM amortization_payments ap
JOIN member_amortizations ma ON ap.amortization_id = ma.amortization_id
JOIN members m ON ma.member_id = m.member_id
ORDER BY m.member_id, ap.payment_date DESC;

-- Payment Trends (Monthly)
SELECT
    DATE_FORMAT(ap.payment_date, '%Y-%m') as payment_month,
    COUNT(ap.payment_id) as total_payments,
    SUM(ap.amount) as total_amount_paid,
    AVG(ap.amount) as average_payment_amount
FROM amortization_payments ap
GROUP BY payment_month
ORDER BY payment_month DESC;

-- Monthly Financial Summary (Income from Payments)
SELECT
    DATE_FORMAT(payment_date, '%Y-%m') as month_year,
    SUM(amount) as total_payment_income
FROM amortization_payments
GROUP BY month_year
ORDER BY month_year DESC;

-- Monthly Financial Summary (Transactions)
SELECT
    DATE_FORMAT(created_at, '%Y-%m') as month_year,
    transaction_type,
    COUNT(*) as transaction_count,
    SUM(amount) as total_amount
FROM member_transactions
GROUP BY month_year, transaction_type
ORDER BY month_year DESC, transaction_type;

-- Quarterly Financial Summary (Income from Payments)
SELECT
    CONCAT(YEAR(payment_date), '-Q', QUARTER(payment_date)) as quarter_year,
    SUM(amount) as total_payment_income
FROM amortization_payments
GROUP BY quarter_year
ORDER BY quarter_year DESC;

-- Quarterly Financial Summary (Transactions)
SELECT
    CONCAT(YEAR(created_at), '-Q', QUARTER(created_at)) as quarter_year,
    transaction_type,
    COUNT(*) as transaction_count,
    SUM(amount) as total_amount
FROM member_transactions
GROUP BY quarter_year, transaction_type
ORDER BY quarter_year DESC, transaction_type;

-- Annual Financial Summary (Income from Payments)
SELECT
    YEAR(payment_date) as year,
    SUM(amount) as total_payment_income
FROM amortization_payments
GROUP BY year
ORDER BY year DESC;

-- Annual Financial Summary (Transactions)
SELECT
    YEAR(created_at) as year,
    transaction_type,
    COUNT(*) as transaction_count,
    SUM(amount) as total_amount
FROM member_transactions
GROUP BY year, transaction_type
ORDER BY year DESC, transaction_type;
