CREATE SCHEMA oarsmc_db;
USE oarsmc_db;
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
DESCRIBE roles;
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    member_id VARCHAR(20) UNIQUE,
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    INDEX idx_email (email),
    INDEX idx_member_id (member_id)
);
DESCRIBE users;
CREATE TABLE invoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    invoice_number VARCHAR(20) NOT NULL UNIQUE,
    amount DECIMAL(10, 2) NOT NULL,
    due_date DATE NOT NULL,
    `description` TEXT,
    `status` ENUM('pending', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
    is_recurring BOOLEAN DEFAULT FALSE,
    recurring_period ENUM('monthly', 'quarterly', 'annually') NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_status (`status`),
    INDEX idx_due_date (due_date)
);
DESCRIBE invoices;
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT NOT NULL,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'check') NOT NULL,
    reference_number VARCHAR(50),
    proof_of_payment VARCHAR(255),
    `status` ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    INDEX idx_payment_date (payment_date),
    INDEX idx_status (`status`)
);
DESCRIBE payments;
CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    `type` ENUM(
        'payment_reminder',
        'overdue_notice',   
        'system_alert',
        'payment_confirmation'
    ) NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    INDEX idx_user_type (user_id, `type`)
);
DESCRIBE notifications;
CREATE TABLE email_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    `status` ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    error_message TEXT,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    INDEX idx_status (`status`)
);
DESCRIBE email_logs;
INSERT INTO roles (role_name)
VALUES ('Administrator'),
    ('Accountant'),
    ('Member');
SELECT *
FROM roles;
INSERT INTO users (
        role_id,
        first_name,
        last_name,
        email,
        `password`,
        member_id
    )
VALUES (
        1,
        'Jerome',
        'Avecilla',
        'jeromesavc@gmail.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'ADMIN001'
    );
SELECT *
FROM users;
INSERT INTO users (
        role_id,
        first_name,
        last_name,
        email,
        password,
        member_id
    )
VALUES (
        2,
        'Maria',
        'Santos',
        'maria.santos@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'ACC001'
    ),
    (
        2,
        'Juan',
        'Cruz',
        'juan.cruz@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'ACC002'
    ),
    (
        3,
        'Ana',
        'Reyes',
        'ana.reyes@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'MEM001'
    ),
    (
        3,
        'Pedro',
        'Garcia',
        'pedro.garcia@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'MEM002'
    ),
    (
        3,
        'Sofia',
        'Luna',
        'sofia.luna@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'MEM003'
    );
SELECT *
FROM users;
INSERT INTO invoices (
        user_id,
        invoice_number,
        amount,
        due_date,
        description,
        status,
        is_recurring,
        recurring_period
    )
VALUES (
        4,
        'INV-2024-001',
        5000.00,
        '2024-02-15',
        'Monthly Contribution - January 2024',
        'pending',
        true,
        'monthly'
    ),
    (
        4,
        'INV-2024-002',
        5000.00,
        '2024-03-15',
        'Monthly Contribution - February 2024',
        'paid',
        true,
        'monthly'
    ),
    (
        5,
        'INV-2024-003',
        15000.00,
        '2024-02-20',
        'Quarterly Membership Fee - Q1 2024',
        'pending',
        true,
        'quarterly'
    ),
    (
        6,
        'INV-2024-004',
        7500.00,
        '2024-02-28',
        'Loan Payment',
        'overdue',
        false,
        NULL
    ),
    (
        5,
        'INV-2024-005',
        3000.00,
        '2024-03-01',
        'Special Assessment Fee',
        'pending',
        false,
        NULL
    );
SELECT *
FROM invoices;
INSERT INTO payments (
        invoice_id,
        user_id,
        amount,
        payment_date,
        payment_method,
        reference_number,
        status,
        notes
    )
VALUES (
        2,
        4,
        5000.00,
        '2024-02-10',
        'bank_transfer',
        'BT-20240210-001',
        'verified',
        'Payment verified by accountant'
    ),
    (
        3,
        5,
        7500.00,
        '2024-02-15',
        'bank_transfer',
        'BT-20240215-001',
        'pending',
        'Awaiting verification'
    ),
    (
        4,
        6,
        2500.00,
        '2024-02-01',
        'cash',
        'CASH-20240201-001',
        'verified',
        'Partial payment received'
    ),
    (
        5,
        5,
        3000.00,
        '2024-02-28',
        'check',
        'CHK-20240228-001',
        'pending',
        'Check clearing pending'
    );
SELECT *
FROM payments;
INSERT INTO notifications (user_id, title, message, type, is_read)
VALUES (
        4,
        'Payment Due Reminder',
        'Your monthly contribution of PHP 5,000 is due on February 15, 2024',
        'payment_reminder',
        false
    ),
    (
        5,
        'Payment Verification',
        'Your payment for INV-2024-003 has been received and is pending verification',
        'payment_confirmation',
        false
    ),
    (
        6,
        'Overdue Notice',
        'Your loan payment for INV-2024-004 is overdue. Please settle immediately.',
        'overdue_notice',
        true
    ),
    (
        4,
        'Payment Confirmed',
        'Your payment for February 2024 contribution has been verified',
        'payment_confirmation',
        false
    );
SELECT *
FROM notifications;
INSERT INTO email_logs (user_id, subject, message, status, sent_at)
VALUES (
        4,
        'Payment Due Reminder',
        'Dear Ana Reyes, this is a reminder that your monthly contribution is due soon.',
        'sent',
        '2024-02-10 10:00:00'
    ),
    (
        5,
        'Payment Received',
        'Dear Pedro Garcia, we have received your payment and it is being processed.',
        'sent',
        '2024-02-15 14:30:00'
    ),
    (
        6,
        'Overdue Payment Notice',
        'Dear Sofia Luna, your loan payment is overdue. Please settle as soon as possible.',
        'sent',
        '2024-02-25 09:15:00'
    );
SELECT *
FROM email_logs;
-- user details with roles
CREATE VIEW vw_user_details AS
SELECT u.user_id,
    u.first_name,
    u.last_name,
    CONCAT(u.first_name, ' ', u.last_name) AS full_name,
    u.email,
    u.member_id,
    u.`status` AS user_status,
    r.role_name,
    u.created_at,
    u.updated_at,
    CONCAT(cb.first_name, ' ', cb.last_name) AS created_by,
    -- Full name of the creator
    CONCAT(ub.first_name, ' ', ub.last_name) AS updated_by -- Full name of the updater
FROM users u
    LEFT JOIN users cb ON u.created_by = cb.user_id
    LEFT JOIN users ub ON u.updated_by = ub.user_id
    INNER JOIN roles r ON u.role_id = r.role_id
ORDER BY u.user_id DESC;
DROP VIEW vw_user_details;
SELECT *
FROM vw_user_details;
-- invoice summaries with payment status
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
SELECT *
FROM vw_invoice_summary;
-- overdue invoices
CREATE VIEW vw_overdue_invoices AS
SELECT i.*,
    CONCAT(u.first_name, ' ', u.last_name) as member_name,
    u.email,
    DATEDIFF(CURRENT_DATE, i.due_date) as days_overdue
FROM invoices i
    JOIN users u ON i.user_id = u.user_id
WHERE i.due_date < CURRENT_DATE
    AND i.status NOT IN ('paid', 'cancelled');
SELECT *
FROM vw_overdue_invoices;
-- payment verification queue
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
SELECT *
FROM vw_payment_verification_queue;
--  member payment history
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
SELECT *
FROM vw_member_payment_history;
SELECT *
FROM vw_member_payment_history
WHERE member_id = 'MEM002';
-- upcoming due payments
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
SELECT *
FROM vw_upcoming_due_payments;
-- monthly collection summary
CREATE VIEW vw_monthly_collection_summary AS
SELECT DATE_FORMAT(p.payment_date, '%Y-%m') as month_year,
    COUNT(DISTINCT p.payment_id) as total_payments,
    SUM(p.amount) as total_collected,
    COUNT(DISTINCT p.user_id) as unique_payers
FROM payments p
WHERE p.status = 'verified'
GROUP BY DATE_FORMAT(p.payment_date, '%Y-%m')
ORDER BY month_year DESC;
SELECT *
FROM vw_monthly_collection_summary;
-- unread notifications summary
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
SELECT *
FROM vw_unread_notifications;
SELECT *
FROM vw_unread_notifications
WHERE member_id = 'MEM001';
DESCRIBE payments;
USE oarsmc_db;
SELECT *
FROM vw_invoice_summary;
SELECT *
FROM vw_member_payment_history
WHERE member_name = 'Pedro Garcia';
SELECT *
FROM roles;
SELECT 1
FROM users
WHERE email = 'jeromesavc@gmail.com'
LIMIT 1;
-- TODO: to be implemented
ALTER TABLE users
ADD COLUMN email_verified_at TIMESTAMP DEFAULT NULL,
    ADD COLUMN first_login_at TIMESTAMP DEFAULT NULL,
    ADD COLUMN profile_img VARCHAR(255) DEFAULT NULL;
ALTER TABLE users
ADD COLUMN created_by INT DEFAULT NULL,
    ADD COLUMN updated_by INT DEFAULT NULL;
ALTER TABLE users
ADD CONSTRAINT fk_created_by FOREIGN KEY (created_by) REFERENCES users(user_id),
    ADD CONSTRAINT fk_updated_by FOREIGN KEY (updated_by) REFERENCES users(user_id);
DESCRIBE users;
SELECT *
FROM users
ORDER BY user_id DESC;
SELECT *
FROM vw_user_details
ORDER BY user_id DESC
LIMIT 10 OFFSET 1;
-- NEW ADDED SQL
CREATE TABLE account_types (
    account_type_id INT PRIMARY KEY AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    minimum_balance DECIMAL(10, 2) DEFAULT 0.00,
    interest_rate DECIMAL(5, 2) DEFAULT 0.00,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT NOT NULL,
    updated_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    FOREIGN KEY (updated_by) REFERENCES users(user_id)
);
CREATE TABLE member_accounts (
    account_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    account_type_id INT NOT NULL,
    account_number VARCHAR(20) NOT NULL UNIQUE,
    current_balance DECIMAL(10, 2) DEFAULT 0.00,
    status ENUM('active', 'inactive', 'suspended', 'closed') DEFAULT 'active',
    opened_date DATE NOT NULL,
    closed_date DATE DEFAULT NULL,
    created_by INT NOT NULL,
    updated_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (account_type_id) REFERENCES account_types(account_type_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    FOREIGN KEY (updated_by) REFERENCES users(user_id),
    INDEX idx_account_number (account_number)
);
INSERT INTO account_types (
        type_name,
        description,
        minimum_balance,
        interest_rate,
        created_by
    )
VALUES (
        'Savings Account',
        'Regular savings account with minimal maintaining balance',
        500.00,
        0.25,
        1
    ),
    (
        'Time Deposit',
        'Fixed term deposit with higher interest rates',
        10000.00,
        3.50,
        1
    ),
    (
        'Fixed Deposit',
        'Long-term deposit account with guaranteed returns',
        25000.00,
        4.75,
        1
    );