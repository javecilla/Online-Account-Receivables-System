-- View for user details with roles
CREATE VIEW vw_user_details AS
SELECT u.user_id,
    u.first_name,
    u.last_name,
    CONCAT(u.first_name, ' ', u.last_name) as full_name,
    u.email,
    u.member_id,
    u.status as user_status,
    r.role_name
FROM users u
    JOIN roles r ON u.role_id = r.role_id;
-- View for invoice summaries with payment status
CREATE VIEW vw_invoice_summary AS
SELECT i.invoice_id,
    i.invoice_number,
    i.amount as total_amount,
    COALESCE(SUM(p.amount), 0) as paid_amount,
    (i.amount - COALESCE(SUM(p.amount), 0)) as remaining_balance,
    i.due_date,
    i.status as invoice_status,
    i.is_recurring,
    i.recurring_period,
    CONCAT(u.first_name, ' ', u.last_name) as member_name,
    u.member_id
FROM invoices i
    LEFT JOIN payments p ON i.invoice_id = p.invoice_id
    JOIN users u ON i.user_id = u.user_id
GROUP BY i.invoice_id;
-- View for overdue invoices
CREATE VIEW vw_overdue_invoices AS
SELECT i.*,
    CONCAT(u.first_name, ' ', u.last_name) as member_name,
    u.email,
    DATEDIFF(CURRENT_DATE, i.due_date) as days_overdue
FROM invoices i
    JOIN users u ON i.user_id = u.user_id
WHERE i.due_date < CURRENT_DATE
    AND i.status NOT IN ('paid', 'cancelled');
-- View for payment verification queue
CREATE VIEW vw_payment_verification_queue AS
SELECT p.payment_id,
    p.amount,
    p.payment_date,
    p.payment_method,
    p.reference_number,
    p.status as payment_status,
    i.invoice_number,
    CONCAT(u.first_name, ' ', u.last_name) as member_name,
    u.member_id
FROM payments p
    JOIN invoices i ON p.invoice_id = i.invoice_id
    JOIN users u ON p.user_id = u.user_id
WHERE p.status = 'pending'
ORDER BY p.payment_date;
-- View for member payment history
CREATE VIEW vw_member_payment_history AS
SELECT u.member_id,
    CONCAT(u.first_name, ' ', u.last_name) as member_name,
    i.invoice_number,
    i.amount as invoice_amount,
    i.due_date,
    p.payment_id,
    p.amount as paid_amount,
    p.payment_date,
    p.payment_method,
    p.status as payment_status
FROM users u
    JOIN invoices i ON u.user_id = i.user_id
    LEFT JOIN payments p ON i.invoice_id = p.invoice_id
ORDER BY p.payment_date DESC;
-- View for upcoming due payments
CREATE VIEW vw_upcoming_due_payments AS
SELECT i.invoice_id,
    i.invoice_number,
    i.amount,
    i.due_date,
    DATEDIFF(i.due_date, CURRENT_DATE) as days_until_due,
    CONCAT(u.first_name, ' ', u.last_name) as member_name,
    u.email,
    u.member_id
FROM invoices i
    JOIN users u ON i.user_id = u.user_id
WHERE i.status = 'pending'
    AND i.due_date >= CURRENT_DATE
ORDER BY i.due_date;
-- View for monthly collection summary
CREATE VIEW vw_monthly_collection_summary AS
SELECT DATE_FORMAT(p.payment_date, '%Y-%m') as month_year,
    COUNT(DISTINCT p.payment_id) as total_payments,
    SUM(p.amount) as total_collected,
    COUNT(DISTINCT p.user_id) as unique_payers
FROM payments p
WHERE p.status = 'verified'
GROUP BY DATE_FORMAT(p.payment_date, '%Y-%m')
ORDER BY month_year DESC;
-- View for unread notifications summary
CREATE VIEW vw_unread_notifications AS
SELECT n.notification_id,
    n.title,
    n.message,
    n.type as notification_type,
    n.created_at,
    CONCAT(u.first_name, ' ', u.last_name) as member_name,
    u.email,
    u.member_id
FROM notifications n
    JOIN users u ON n.user_id = u.user_id
WHERE n.is_read = FALSE
ORDER BY n.created_at DESC;