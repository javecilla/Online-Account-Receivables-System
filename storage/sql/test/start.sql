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