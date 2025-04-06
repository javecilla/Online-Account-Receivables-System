DROP DATABASE oarsmc_db;
CREATE DATABASE oarsmc_db;
USE oarsmc_db;
CREATE TABLE account_roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE accounts (
    account_id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    account_uid VARCHAR(100) NOT NULL UNIQUE,
    -- A581822
    email VARCHAR(100) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP DEFAULT NULL,
    username VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    account_status ENUM('active', 'inactive') DEFAULT 'inactive',
    first_login_at TIMESTAMP DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_accounts_role_id FOREIGN KEY (role_id) REFERENCES account_roles(role_id),
    INDEX idx_account_uid (account_uid),
    INDEX idx_email (email)
);
CREATE TABLE employees (
    employee_id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT DEFAULT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) DEFAULT NULL,
    last_name VARCHAR(50) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    salary DECIMAL(10, 2) DEFAULT 0.00,
    rata DECIMAL(10, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_employees_account_id FOREIGN KEY (account_id) REFERENCES accounts(account_id) ON DELETE
    SET NULL ON UPDATE CASCADE
);
CREATE TABLE member_types (
    type_id INT PRIMARY KEY AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL UNIQUE,
    type_description TEXT,
    minimum_balance DECIMAL(10, 2) DEFAULT 0.00,
    interest_rate DECIMAL(5, 2) DEFAULT 0.00,
    penalty_rate DECIMAL(5, 3) DEFAULT 0.010 COMMENT 'Percentage rate for penalties (e.g., 0.010 = 1%)',
    minimum_penalty_fee DECIMAL(10, 2) DEFAULT 50.00 COMMENT 'Minimum penalty amount to charge',
    maximum_penalty_fee DECIMAL(10, 2) DEFAULT 500.00 COMMENT 'Maximum penalty amount to charge',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE members (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT DEFAULT NULL,
    type_id INT NOT NULL,
    member_uid VARCHAR(20) NOT NULL UNIQUE,
    -- M65123
    membership_status ENUM('active', 'inactive', 'suspended', 'closed') DEFAULT 'active',
    current_balance DECIMAL(10, 2) DEFAULT 0.00,
    opened_date DATE NOT NULL,
    closed_date DATE DEFAULT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) DEFAULT NULL,
    last_name VARCHAR(50) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    house_address VARCHAR(200),
    -- Ph7, Blk3, Lot 24, Residence III
    barangay VARCHAR(100),
    -- Brgy. Mapulang Lupa
    municipality VARCHAR(100),
    -- Pandi
    province VARCHAR(100),
    -- Bulacan
    region VARCHAR(100),
    -- Region 3
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_members_account_id FOREIGN KEY (account_id) REFERENCES accounts(account_id) ON DELETE
    SET NULL ON UPDATE CASCADE,
        CONSTRAINT fk_members_type_id FOREIGN KEY (type_id) REFERENCES member_types(type_id),
        INDEX idx_member_uid (member_uid)
);
CREATE TABLE email_verification_codes (
    evc_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    `code` VARCHAR(10) NOT NULL,
    created_at DATETIME DEFAULT NOW(),
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    UNIQUE (`code`),
    INDEX idx_email_code (email, `code`)
);
CREATE TABLE account_otp_codes (
    aoc_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    `code` VARCHAR(6) NOT NULL,
    created_at DATETIME DEFAULT NOW(),
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    UNIQUE (`code`),
    INDEX idx_email_code (email, `code`)
);
CREATE TABLE password_reset_codes (
    prc_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    `code` VARCHAR(6) NOT NULL,
    created_at DATETIME DEFAULT NOW(),
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    UNIQUE (`code`),
    INDEX idx_email_code (email, `code`)
);
-- Transaction Management Tables
CREATE TABLE member_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    transaction_type ENUM('deposit', 'withdrawal', 'interest', 'fee') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    previous_balance DECIMAL(10, 2) NOT NULL,
    new_balance DECIMAL(10, 2) NOT NULL,
    reference_number VARCHAR(20) NOT NULL UNIQUE,
    notes TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(member_id),
    FOREIGN KEY (created_by) REFERENCES accounts(account_id),
    INDEX idx_reference_number (reference_number),
    INDEX idx_transaction_type (transaction_type),
    INDEX idx_created_at (created_at)
);
-- Amortization Management Tables
CREATE TABLE amortization_types (
    type_id INT PRIMARY KEY AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL UNIQUE,
    -- 'Educational', 'Calamity', etc.
    description TEXT,
    interest_rate DECIMAL(5, 2) DEFAULT 0.00,
    -- Annual interest rate
    term_months INT NOT NULL,
    -- Standard term length
    minimum_amount DECIMAL(10, 2) DEFAULT 1000.00,
    maximum_amount DECIMAL(10, 2) DEFAULT 50000.00,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
/*
 Member Amortizations Table
 This table stores loan amortization records for members.
 
 Columns:
 - amortization_id: Unique identifier for each amortization record
 - member_id: References the member who took the loan
 - type_id: References the type of loan (educational, calamity, etc.)
 - principal_amount: The original loan amount without interest
 - monthly_amount: Fixed amount to be paid each month (principal + interest / term_months)
 - remaining_balance: Total amount still to be paid (decreases with each payment)
 Initially = principal + total interest
 Updates as payments are made: remaining_balance = remaining_balance - payment_amount
 - start_date: When the loan period begins
 - end_date: When the loan should be fully paid
 - status: Current state of the loan
 -      active: Loan is ongoing with regular payments
 -      completed: Loan has been fully paid
 -      defaulted: Payments are severely overdue
 - created_at: Timestamp when the loan was created
 */
CREATE TABLE member_amortizations (
    amortization_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    type_id INT NOT NULL,
    principal_amount DECIMAL(10, 2) NOT NULL,
    monthly_amount DECIMAL(10, 2) NOT NULL,
    remaining_balance DECIMAL(10, 2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    `status` ENUM('active', 'completed', 'defaulted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(member_id),
    FOREIGN KEY (type_id) REFERENCES amortization_types(type_id),
    INDEX idx_status (status),
    INDEX idx_dates (start_date, end_date)
);
CREATE TABLE amortization_payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    amortization_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    reference_number VARCHAR(20) NOT NULL UNIQUE,
    notes TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (amortization_id) REFERENCES member_amortizations(amortization_id),
    FOREIGN KEY (created_by) REFERENCES accounts(account_id),
    INDEX idx_payment_date (payment_date),
    INDEX idx_reference_number (reference_number)
);