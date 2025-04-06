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
    FOREIGN KEY (role_id) REFERENCES account_roles(role_id),
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
    FOREIGN KEY (account_id) REFERENCES accounts(account_id)
);
CREATE TABLE member_types (
    type_id INT PRIMARY KEY AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL UNIQUE,
    type_description TEXT,
    minimum_balance DECIMAL(10, 2) DEFAULT 0.00,
    interest_rate DECIMAL(5, 2) DEFAULT 0.00,
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
    FOREIGN KEY (account_id) REFERENCES accounts(account_id),
    FOREIGN KEY (type_id) REFERENCES member_types(type_id),
    INDEX idx_member_uid (member_uid)
);