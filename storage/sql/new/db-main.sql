USE oarsmc_db;
ALTER TABLE employees
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
DESCRIBE employees;
ALTER TABLE members
MODIFY COLUMN account_id INT DEFAULT NULL;
ALTER TABLE members
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
DESCRIBE members;
CREATE INDEX idx_username ON accounts (username);
DROP VIEW vw_account_details;
SELECT *
FROM vw_account_details;
SELECT *
FROM vw_account_details
WHERE email = 'aasdas@gmail.com';
SELECT *
FROM accounts
WHERE email = 'aasdas@gmail.com';
SELECT *
FROM email_verification_codes;
SELECT *
FROM account_otp_codes;
SELECT *
FROM accounts;
SELECT *
FROM password_reset_codes;
SELECT *
FROM account_roles;
INSERT INTO employees (
        account_id,
        first_name,
        middle_name,
        last_name,
        contact_number,
        salary,
        rata
    )
VALUES (
        9,
        'Jerome',
        'Sotel',
        'Avecil',
        '+639772645533',
        90000.00,
        5000.00
    );
DROP VIEW vw_employee_details;
SELECT *
FROM vw_employee_details;
SELECT *
FROM employees;
SELECT *
FROM vw_employee_details
WHERE employee_id = 3
    OR account_id = 3
LIMIT 1;
INSERT INTO members (
        account_id,
        type_id,
        member_uid,
        membership_status,
        current_balance,
        opened_date,
        first_name,
        middle_name,
        last_name,
        contact_number,
        house_address,
        barangay,
        municipality,
        province,
        region
    )
VALUES (
        9,
        3,
        'M20041',
        'active',
        10000.00,
        CURRENT_DATE,
        'Jerome',
        'Sotel',
        'Avecilla',
        '+639772465533',
        'Blk 8 Lot 19',
        'Brgy. Mapulang Lupa',
        'Pandi',
        'Bulacan',
        'Region 3'
    );
SELECT *
FROM accounts;
DROP VIEW vw_member_details;
SELECT *
FROM vw_member_details;
ALTER TABLE members DROP FOREIGN KEY fk_members_type_id;
TRUNCATE TABLE member_types;
TRUNCATE TABLE members;
SELECT *
FROM member_types;
SELECT *
FROM members;
DESCRIBE members;
SELECT *
FROM vw_member_details
WHERE account_id = 9
LIMIT 1;