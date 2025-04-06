USE oarsmc_db;
DROP VIEW IF EXISTS vw_account_details;
DROP VIEW IF EXISTS vw_employee_details;
DROP VIEW IF EXISTS vw_member_details;
DROP VIEW IF EXISTS vw_active_accounts_summary;
DROP VIEW IF EXISTS vw_member_locations;
DROP VIEW IF EXISTS vw_membership_status_summary;
DROP VIEW IF EXISTS vw_amortization_details;
DROP VIEW IF EXISTS vw_amortization_payment_summary;
DROP VIEW IF EXISTS vw_daily_transaction_summary;
DROP VIEW IF EXISTS vw_monthly_balance_trends;
DROP VIEW IF EXISTS vw_loan_performance_analytics;
DROP VIEW IF EXISTS vw_payment_collection_efficiency;
DROP VIEW IF EXISTS vw_member_growth_analysis;
DROP VIEW IF EXISTS vw_risk_assessment_dashboard;
SELECT *
FROM vw_account_details;
SELECT *
FROM vw_employee_details;
SELECT *
FROM vw_member_details;
SELECT *
FROM vw_active_accounts_summary;
SELECT *
FROM vw_member_locations;
SELECT *
FROM vw_membership_status_summary;
SELECT *
FROM vw_amortization_details;
SELECT *
FROM vw_amortization_payment_summary;
SELECT *
FROM vw_daily_transaction_summary;
SELECT *
FROM vw_monthly_balance_trends;
SELECT *
FROM vw_loan_performance_analytics;
SELECT *
FROM vw_payment_collection_efficiency;
SELECT *
FROM vw_member_growth_analysis;
SELECT *
FROM vw_risk_assessment_dashboard;
-- Account details with roles view
CREATE VIEW vw_account_details AS
SELECT a.account_id,
    a.account_uid,
    a.email,
    a.username,
    a.account_status,
    a.email_verified_at,
    a.first_login_at,
    ar.role_name,
    a.created_at,
    a.updated_at
FROM accounts a
    INNER JOIN account_roles ar ON a.role_id = ar.role_id;
-- Employee details with account info
CREATE VIEW vw_employee_details AS
SELECT e.employee_id,
    e.first_name,
    e.middle_name,
    e.last_name,
    CONCAT(
        e.first_name,
        ' ',
        IFNULL(CONCAT(LEFT(e.middle_name, 1), '. '), ''),
        e.last_name
    ) as full_name,
    e.contact_number,
    e.salary,
    e.rata,
    a.account_uid,
    a.email,
    a.account_status,
    ar.role_name,
    e.created_at,
    e.updated_at
FROM employees e
    LEFT JOIN accounts a ON e.account_id = a.account_id
    LEFT JOIN account_roles ar ON a.role_id = ar.role_id;
-- Member details with account and type info
CREATE VIEW vw_member_details AS
SELECT m.member_id,
    m.member_uid,
    m.first_name,
    m.middle_name,
    m.last_name,
    CONCAT(
        m.first_name,
        ' ',
        IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''),
        m.last_name
    ) as full_name,
    m.contact_number,
    m.membership_status,
    m.current_balance,
    m.opened_date,
    m.closed_date,
    m.house_address,
    m.barangay,
    m.municipality,
    m.province,
    m.region,
    CONCAT(
        m.house_address,
        ', ',
        m.barangay,
        ', ',
        m.municipality,
        ', ',
        m.province,
        ', ',
        m.region
    ) as full_address,
    mt.type_name as membership_type,
    mt.minimum_balance,
    mt.interest_rate,
    a.account_uid,
    a.email,
    a.account_status,
    a.created_at as registered_at
FROM members m
    INNER JOIN member_types mt ON m.type_id = mt.type_id
    LEFT JOIN accounts a ON m.account_id = a.account_id;
-- Active accounts summary by type
CREATE VIEW vw_active_accounts_summary AS
SELECT mt.type_name,
    COUNT(m.member_id) as total_members,
    SUM(m.current_balance) as total_balance,
    MIN(m.current_balance) as min_balance,
    MAX(m.current_balance) as max_balance,
    AVG(m.current_balance) as avg_balance
FROM member_types mt
    LEFT JOIN members m ON mt.type_id = m.type_id
    AND m.membership_status = 'active'
GROUP BY mt.type_id,
    mt.type_name;
-- Member locations summary
CREATE VIEW vw_member_locations AS
SELECT province,
    municipality,
    barangay,
    COUNT(member_id) as member_count
FROM members
GROUP BY province,
    municipality,
    barangay
ORDER BY province,
    municipality,
    barangay;
-- Membership status summary
CREATE VIEW vw_membership_status_summary AS
SELECT mt.type_name,
    m.membership_status,
    COUNT(m.member_id) as total_members,
    SUM(m.current_balance) as total_balance
FROM member_types mt
    LEFT JOIN members m ON mt.type_id = m.type_id
GROUP BY mt.type_name,
    m.membership_status
ORDER BY mt.type_name,
    m.membership_status;
-- Amortization details with payment summary
CREATE VIEW vw_amortization_details AS
SELECT ma.amortization_id,
    ma.member_id,
    ma.type_id,
    ma.principal_amount,
    ma.monthly_amount,
    ma.remaining_balance,
    ma.start_date,
    ma.end_date,
    ma.`status`,
    ma.created_at,
    `at`.type_name,
    `at`.`description`,
    `at`.interest_rate,
    IFNULL(SUM(ap.amount), 0) as total_paid,
    (ma.remaining_balance - IFNULL(SUM(ap.amount), 0)) as balance_due,
    m.current_balance,
    CONCAT(
        m.first_name,
        ' ',
        IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''),
        m.last_name
    ) as full_name
FROM member_amortizations ma
    JOIN amortization_types `at` ON ma.type_id = `at`.type_id
    LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
    INNER JOIN members m ON ma.member_id = m.member_id
GROUP BY ma.amortization_id,
    ma.member_id,
    ma.type_id,
    ma.principal_amount,
    ma.monthly_amount,
    ma.remaining_balance,
    ma.start_date,
    ma.end_date,
    ma.`status`,
    ma.created_at,
    `at`.type_name,
    `at`.`description`,
    `at`.interest_rate;
-- Payment Summary View for each member's amortization
CREATE VIEW vw_amortization_payment_summary AS
SELECT m.member_id,
    m.member_uid,
    CONCAT(
        m.first_name,
        ' ',
        IFNULL(CONCAT(LEFT(m.middle_name, 1), '. '), ''),
        m.last_name
    ) as full_name,
    ma.amortization_id,
    at.type_name,
    ma.principal_amount,
    ma.monthly_amount,
    ma.remaining_balance,
    COUNT(ap.payment_id) as total_payments,
    COALESCE(SUM(ap.amount), 0) as total_amount_paid,
    ma.principal_amount + (
        ma.principal_amount * (at.interest_rate / 100) * (
            TIMESTAMPDIFF(MONTH, ma.start_date, ma.end_date) / 12
        )
    ) as total_payable,
    (
        SELECT MIN(payment_date)
        FROM amortization_payments
        WHERE amortization_id = ma.amortization_id
    ) as first_payment_date,
    (
        SELECT MAX(payment_date)
        FROM amortization_payments
        WHERE amortization_id = ma.amortization_id
    ) as last_payment_date,
    ma.start_date,
    ma.end_date,
    ma.status,
    CASE
        WHEN ma.status = 'completed' THEN 'Completed'
        WHEN CURRENT_DATE > ma.end_date
        AND ma.status = 'active' THEN 'Overdue'
        WHEN ma.status = 'defaulted' THEN 'Defaulted'
        ELSE 'Active'
    END as payment_status
FROM members m
    INNER JOIN member_amortizations ma ON m.member_id = ma.member_id
    INNER JOIN amortization_types at ON ma.type_id = at.type_id
    LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
GROUP BY m.member_id,
    m.member_uid,
    m.first_name,
    m.middle_name,
    m.last_name,
    ma.amortization_id,
    at.type_name,
    ma.principal_amount,
    ma.monthly_amount,
    ma.remaining_balance,
    ma.start_date,
    ma.end_date,
    ma.status;
-- Daily Transaction Summary View
CREATE VIEW vw_daily_transaction_summary AS
SELECT DATE(created_at) as transaction_date,
    transaction_type,
    COUNT(*) as transaction_count,
    SUM(amount) as total_amount,
    MIN(amount) as min_amount,
    MAX(amount) as max_amount,
    AVG(amount) as avg_amount
FROM member_transactions
GROUP BY DATE(created_at),
    transaction_type
ORDER BY transaction_date DESC;
-- Monthly Account Balance Trends
CREATE VIEW vw_monthly_balance_trends AS
SELECT DATE_FORMAT(mt.created_at, '%Y-%m') as month_year,
    mt.type_name as account_type,
    COUNT(DISTINCT m.member_id) as total_members,
    SUM(mt2.amount) as total_transactions,
    SUM(
        CASE
            WHEN mt2.transaction_type IN ('deposit', 'interest') THEN mt2.amount
            ELSE 0
        END
    ) as total_credits,
    SUM(
        CASE
            WHEN mt2.transaction_type IN ('withdrawal', 'fee') THEN mt2.amount
            ELSE 0
        END
    ) as total_debits,
    AVG(m.current_balance) as average_balance
FROM member_types mt
    JOIN members m ON m.type_id = mt.type_id
    LEFT JOIN member_transactions mt2 ON m.member_id = mt2.member_id
GROUP BY DATE_FORMAT(mt.created_at, '%Y-%m'),
    mt.type_name
ORDER BY month_year DESC,
    account_type;
-- Loan Performance Analytics
CREATE VIEW vw_loan_performance_analytics AS
SELECT at.type_name as loan_type,
    COUNT(ma.amortization_id) as total_loans,
    SUM(ma.principal_amount) as total_principal,
    SUM(ma.remaining_balance) as total_remaining,
    AVG(ma.monthly_amount) as avg_monthly_payment,
    COUNT(
        CASE
            WHEN ma.status = 'completed' THEN 1
        END
    ) as completed_loans,
    COUNT(
        CASE
            WHEN ma.status = 'defaulted' THEN 1
        END
    ) as defaulted_loans,
    COUNT(
        CASE
            WHEN ma.status = 'active' THEN 1
        END
    ) as active_loans,
    ROUND(
        (
            COUNT(
                CASE
                    WHEN ma.status = 'defaulted' THEN 1
                END
            ) * 100.0 / COUNT(*)
        ),
        2
    ) as default_rate,
    AVG(
        DATEDIFF(
            COALESCE(
                (
                    SELECT MAX(payment_date)
                    FROM amortization_payments
                    WHERE amortization_id = ma.amortization_id
                ),
                CURRENT_DATE
            ),
            ma.start_date
        )
    ) as avg_loan_duration_days
FROM amortization_types at
    LEFT JOIN member_amortizations ma ON at.type_id = ma.type_id
GROUP BY at.type_name;
-- Payment Collection Efficiency
CREATE VIEW vw_payment_collection_efficiency AS
SELECT at.type_name as loan_type,
    YEAR(ap.payment_date) as year,
    MONTH(ap.payment_date) as month,
    COUNT(DISTINCT ma.amortization_id) as active_loans,
    COUNT(ap.payment_id) as payments_made,
    SUM(ap.amount) as collected_amount,
    SUM(ma.monthly_amount) as expected_amount,
    ROUND(
        (
            SUM(ap.amount) * 100.0 / NULLIF(SUM(ma.monthly_amount), 0)
        ),
        2
    ) as collection_efficiency,
    AVG(DATEDIFF(ap.payment_date, ma.start_date)) as avg_days_to_pay
FROM amortization_types at
    JOIN member_amortizations ma ON at.type_id = ma.type_id
    LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
WHERE ma.status = 'active'
GROUP BY at.type_name,
    YEAR(ap.payment_date),
    MONTH(ap.payment_date)
ORDER BY year DESC,
    month DESC;
-- Member Growth Analysis
CREATE VIEW vw_member_growth_analysis AS
SELECT DATE_FORMAT(opened_date, '%Y-%m') as month_year,
    COUNT(*) as new_members,
    SUM(
        CASE
            WHEN membership_status = 'active' THEN 1
            ELSE 0
        END
    ) as active_members,
    SUM(
        CASE
            WHEN membership_status = 'inactive' THEN 1
            ELSE 0
        END
    ) as inactive_members,
    SUM(
        CASE
            WHEN membership_status = 'suspended' THEN 1
            ELSE 0
        END
    ) as suspended_members,
    SUM(
        CASE
            WHEN membership_status = 'closed' THEN 1
            ELSE 0
        END
    ) as closed_members,
    AVG(current_balance) as avg_opening_balance
FROM members
GROUP BY DATE_FORMAT(opened_date, '%Y-%m')
ORDER BY month_year DESC;
-- Risk Assessment Dashboard
CREATE VIEW vw_risk_assessment_dashboard AS
SELECT mt.type_name as account_type,
    COUNT(DISTINCT m.member_id) as total_accounts,
    SUM(
        CASE
            WHEN m.current_balance < mt.minimum_balance THEN 1
            ELSE 0
        END
    ) as accounts_below_minimum,
    ROUND(
        (
            SUM(
                CASE
                    WHEN m.current_balance < mt.minimum_balance THEN 1
                    ELSE 0
                END
            ) * 100.0 / COUNT(*)
        ),
        2
    ) as percent_below_minimum,
    COUNT(
        DISTINCT CASE
            WHEN ma.status = 'defaulted' THEN m.member_id
        END
    ) as members_with_defaults,
    ROUND(
        (
            COUNT(
                DISTINCT CASE
                    WHEN ma.status = 'defaulted' THEN m.member_id
                END
            ) * 100.0 / COUNT(DISTINCT m.member_id)
        ),
        2
    ) as default_rate,
    AVG(m.current_balance) as avg_balance,
    MIN(m.current_balance) as min_balance,
    MAX(m.current_balance) as max_balance
FROM member_types mt
    JOIN members m ON mt.type_id = m.type_id
    LEFT JOIN member_amortizations ma ON m.member_id = ma.member_id
GROUP BY mt.type_name;