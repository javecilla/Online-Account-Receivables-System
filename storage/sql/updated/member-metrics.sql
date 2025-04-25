USE oarsmc_db;

-- SQL Queries for Member Dashboard Metrics
-- Replace :member_id with the actual member ID when executing.

-- 1. Account Balance Metrics
SELECT
    m.current_balance AS total_current_balance,
    m.credit_balance,
    (SELECT COALESCE(SUM(amount), 0) 
     FROM member_transactions 
     WHERE member_id = m.member_id 
       AND transaction_type = 'withdrawal' 
       AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)) AS total_withdrawals_last_30d
FROM 
    members m
-- WHERE m.member_id = :member_id;

-- 2. Savings Goal Metrics (Conditional - Run if member type is savings-related)
-- Note: Target is based on member_types.minimum_balance. Adjust logic if a different target is needed.
SELECT 
    mt.type_name AS member_type_name,
    m.current_balance AS savings_current_balance,
    mt.minimum_balance AS savings_target_balance,
    CASE 
        WHEN mt.minimum_balance > 0 THEN (m.current_balance / mt.minimum_balance) * 100
        ELSE 0 
    END AS savings_progress_percentage
FROM 
    members m
JOIN 
    member_types mt ON m.type_id = mt.type_id
WHERE mt.type_name IN ('Savings Account', 'Time Deposit', 'Fixed Deposit', 'Special Savings', 'Youth Savings')
	AND m.member_id = 8;

-- 3. Active Loans Metrics (Conditional - Run if member has active/pending loans)
SELECT 
    COUNT(ma.amortization_id) AS total_active_loans_count,
    COALESCE(SUM(ma.principal_amount), 0) AS total_loan_principal,
    COALESCE(SUM(CASE WHEN ma.status = 'overdue' THEN ma.remaining_balance ELSE 0 END), 0) AS total_overdue_amount,
    COALESCE(SUM(CASE WHEN ma.status = 'overdue' THEN 1 ELSE 0 END), 0) AS overdue_loans_count
FROM 
    member_amortizations ma
WHERE ma.status IN ('pending', 'overdue') -- Consider including 'approved' if needed
    AND ma.approval = 'approved' -- Only count approved loans
    AND ma.member_id = 16;

-- 4. Account Status Metrics
SELECT 
    m.membership_status,
    m.opened_date AS member_since_date,
    -- Determine overall loan payment status (simplified)
    CASE 
        WHEN EXISTS (SELECT 1 FROM member_amortizations WHERE member_id = m.member_id AND status = 'overdue' AND approval = 'approved') THEN 'Overdue Payments'
        WHEN EXISTS (SELECT 1 FROM member_amortizations WHERE member_id = m.member_id AND status = 'pending' AND approval = 'approved') THEN 'Payments Pending'
        WHEN EXISTS (SELECT 1 FROM member_amortizations WHERE member_id = m.member_id AND approval = 'approved') THEN 'On Time'
        ELSE 'No Active Loans'
    END AS loan_payment_status
FROM 
    members m
WHERE m.member_id = 16;

-- 5. Upcoming Payments (Example: Next 5 loan payments)
-- Note: This requires calculating the next due date based on start_date, term, and payments made.
-- The logic below is a simplified example assuming monthly payments and needs refinement based on your exact payment schedule logic.
SELECT 
    ma.amortization_id,
    at.type_name AS loan_type,
    ma.monthly_amount,
    -- Placeholder for calculated next due date - Requires more complex logic
    DATE_ADD(ma.start_date, INTERVAL (SELECT COUNT(*) FROM amortization_payments WHERE amortization_id = ma.amortization_id) MONTH) AS next_due_date_estimate
FROM 
    member_amortizations ma
JOIN
    amortization_types at ON ma.type_id = at.type_id
WHERE ma.status IN ('pending', 'overdue') -- Or just 'pending' if overdue handled separately
    AND ma.approval = 'approved'
    AND ma.member_id = 16
ORDER BY 
    next_due_date_estimate ASC
LIMIT 5;
