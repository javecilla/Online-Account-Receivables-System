-- Additional test users (password: password123)
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
-- Sample invoices
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
-- Sample payments
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
-- Sample notifications
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
-- Sample email logs
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