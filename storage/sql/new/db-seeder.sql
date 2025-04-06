USE oarsmc_db;
-- Account Roles
INSERT INTO account_roles (role_name)
VALUES ('Administrator'),
    ('Accountant'),
    ('Member');
-- Accounts (Authentication)
INSERT INTO accounts (
        role_id,
        account_uid,
        email,
        username,
        password,
        account_status
    )
VALUES -- Admin account
    (
        1,
        'A581822',
        'admin@example.com',
        'admin',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    -- Accountant accounts
    (
        2,
        'A581823',
        'accountant1@example.com',
        'accountant1',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        2,
        'A581824',
        'accountant2@example.com',
        'accountant2',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    -- Member accounts
    (
        3,
        'A581825',
        'member1@example.com',
        'member1',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581826',
        'member2@example.com',
        'member2',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    -- Additional Member Accounts
    (
        3,
        'A581827',
        'carlos.santos@example.com',
        'carlos.santos',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    -- Test Accounts for Overdue Scenarios
    (
        3,
        'A581837',
        'test.overdue1@example.com',
        'overdue.test1',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581838',
        'test.overdue2@example.com',
        'overdue.test2',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581839',
        'test.overdue3@example.com',
        'overdue.test3',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581840',
        'test.overdue4@example.com',
        'overdue.test4',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581841',
        'test.overdue5@example.com',
        'overdue.test5',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581828',
        'diana.cruz@example.com',
        'diana.cruz',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581829',
        'eduardo.reyes@example.com',
        'eduardo.reyes',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581830',
        'fatima.garcia@example.com',
        'fatima.garcia',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581831',
        'gabriel.torres@example.com',
        'gabriel.torres',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581832',
        'helena.lopez@example.com',
        'helena.lopez',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581833',
        'ivan.mendoza@example.com',
        'ivan.mendoza',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581834',
        'julia.ramos@example.com',
        'julia.ramos',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581835',
        'kevin.santos@example.com',
        'kevin.santos',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    ),
    (
        3,
        'A581836',
        'luna.ferrer@example.com',
        'luna.ferrer',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'active'
    );
-- Employees
INSERT INTO employees (
        account_id,
        first_name,
        middle_name,
        last_name,
        contact_number,
        salary,
        rata
    )
VALUES -- Admin
    (
        1,
        'John',
        'Doe',
        'Smith',
        '+639171234567',
        50000.00,
        5000.00
    ),
    -- Accountants
    (
        2,
        'Jane',
        'Marie',
        'Wilson',
        '+639181234567',
        35000.00,
        3000.00
    ),
    (
        3,
        'Robert',
        'James',
        'Brown',
        '+639191234567',
        35000.00,
        3000.00
    );
-- Member Types
TRUNCATE TABLE member_types;
INSERT INTO member_types (
        type_name,
        type_description,
        minimum_balance,
        interest_rate,
        penalty_rate,
        minimum_penalty_fee,
        maximum_penalty_fee
    )
VALUES (
        'Savings Account',
        'Regular savings account with easy access and basic interest rates',
        500.00,
        0.25,
        0.010,
        -- 1.0% penalty rate
        50.00,
        -- minimum penalty
        500.00 -- maximum penalty
    ),
    (
        'Time Deposit',
        'Fixed-term deposit with higher interest rates, minimum 30 days lock-in',
        10000.00,
        4.50,
        0.020,
        -- 2.0% penalty rate
        100.00,
        -- minimum penalty
        1000.00 -- maximum penalty
    ),
    (
        'Fixed Deposit',
        'Long-term savings with guaranteed returns, minimum 1 year lock-in',
        25000.00,
        6.00,
        0.030,
        -- 3.0% penalty rate
        200.00,
        -- minimum penalty
        2000.00 -- maximum penalty
    ),
    (
        'Special Savings',
        'High-yield savings account with maintaining balance',
        50000.00,
        3.75,
        0.015,
        -- 1.5% penalty rate
        150.00,
        -- minimum penalty
        1500.00 -- maximum penalty
    ),
    (
        'Youth Savings',
        'Savings account for members under 21 years old with lower maintaining balance',
        200.00,
        0.50,
        0.005,
        -- 0.5% penalty rate
        25.00,
        -- minimum penalty
        250.00 -- maximum penalty
    ),
    (
        'Loan',
        'Credit account for borrowing money with regular interest charges',
        0.00,
        -- Loans don't require minimum balance
        12.00,
        -- Base interest rate for loans
        0.050,
        -- 5.0% penalty rate (higher because it's for late payments)
        500.00,
        -- Higher minimum penalty for loan defaults
        5000.00 -- Higher maximum penalty for loan defaults
    );
-- Members
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
        4,
        1,
        'M65123',
        'active',
        5000.00,
        CURRENT_DATE,
        'Maria',
        'Santos',
        'Cruz',
        '+639201234567',
        'Block 5 Lot 12',
        'San Jose',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        5,
        2,
        'M65124',
        'active',
        15000.00,
        CURRENT_DATE,
        'Pedro',
        'Garcia',
        'Luna',
        '+639211234567',
        '123 Main Street',
        'Santa Maria',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    -- Savings Account Members (type_id: 1)
    (
        6,
        1,
        'M65125',
        'active',
        2500.00,
        CURRENT_DATE,
        'Carlos',
        'Mendez',
        'Santos',
        '+639221234567',
        'Block 1 Lot 5',
        'San Vicente',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        7,
        1,
        'M65126',
        'active',
        3500.00,
        CURRENT_DATE,
        'Diana',
        'Rivera',
        'Cruz',
        '+639231234567',
        'Block 2 Lot 8',
        'San Vicente',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        8,
        1,
        'M65127',
        'active',
        1800.00,
        CURRENT_DATE,
        'Eduardo',
        'Luna',
        'Reyes',
        '+639241234567',
        'Block 3 Lot 12',
        'Santa Cruz',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    -- Time Deposit Members (type_id: 2)
    (
        9,
        2,
        'M65128',
        'active',
        15000.00,
        CURRENT_DATE,
        'Fatima',
        'Santos',
        'Garcia',
        '+639251234567',
        'Block 4 Lot 15',
        'Santa Cruz',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        10,
        2,
        'M65129',
        'active',
        25000.00,
        CURRENT_DATE,
        'Gabriel',
        'Cruz',
        'Torres',
        '+639261234567',
        'Block 5 Lot 18',
        'Poblacion',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        11,
        2,
        'M65130',
        'active',
        20000.00,
        CURRENT_DATE,
        'Helena',
        'Reyes',
        'Lopez',
        '+639271234567',
        'Block 6 Lot 21',
        'Poblacion',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    -- Fixed Deposit Members (type_id: 3)
    (
        12,
        3,
        'M65131',
        'active',
        50000.00,
        CURRENT_DATE,
        'Ivan',
        'Garcia',
        'Mendoza',
        '+639281234567',
        'Block 7 Lot 24',
        'Masagana',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        13,
        3,
        'M65132',
        'active',
        75000.00,
        CURRENT_DATE,
        'Julia',
        'Torres',
        'Ramos',
        '+639291234567',
        'Block 8 Lot 27',
        'Masagana',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    -- Special Savings Members (type_id: 4)
    (
        14,
        4,
        'M65133',
        'active',
        100000.00,
        CURRENT_DATE,
        'Kevin',
        'Lopez',
        'Santos',
        '+639301234567',
        'Block 9 Lot 30',
        'Cacarong',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        15,
        4,
        'M65134',
        'active',
        150000.00,
        CURRENT_DATE,
        'Luna',
        'Mendoza',
        'Ferrer',
        '+639311234567',
        'Block 10 Lot 33',
        'Cacarong',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    -- Test Members with Overdue Loans
    (
        16,
        6,
        'M65135',
        'active',
        5000.00,
        CURRENT_DATE,
        'Test',
        'Overdue',
        'One',
        '+639321234567',
        'Block 11 Lot 36',
        'San Jose',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        17,
        6,
        'M65136',
        'active',
        7500.00,
        CURRENT_DATE,
        'Test',
        'Overdue',
        'Two',
        '+639331234567',
        'Block 12 Lot 39',
        'San Jose',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        18,
        6,
        'M65137',
        'active',
        10000.00,
        CURRENT_DATE,
        'Test',
        'Overdue',
        'Three',
        '+639341234567',
        'Block 13 Lot 42',
        'Santa Maria',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        19,
        6,
        'M65138',
        'active',
        15000.00,
        CURRENT_DATE,
        'Test',
        'Overdue',
        'Four',
        '+639351234567',
        'Block 14 Lot 45',
        'Santa Maria',
        'Pandi',
        'Bulacan',
        'Region 3'
    ),
    (
        20,
        6,
        'M65139',
        'active',
        20000.00,
        CURRENT_DATE,
        'Test',
        'Overdue',
        'Five',
        '+639361234567',
        'Block 15 Lot 48',
        'Santa Maria',
        'Pandi',
        'Bulacan',
        'Region 3'
    );
-- Sample Data for Amortization Types
TRUNCATE TABLE amortization_types;
INSERT INTO amortization_types (
        type_name,
        description,
        interest_rate,
        term_months,
        minimum_amount,
        maximum_amount
    )
VALUES (
        'Educational Loan',
        'Financial assistance for educational expenses including tuition fees, books, and other school-related costs',
        6.00,
        -- 6% annual interest
        12,
        -- 1 year term
        5000.00,
        50000.00
    ),
    (
        'Calamity Loan',
        'Emergency financial aid for members affected by natural disasters or calamities',
        4.00,
        -- Lower 4% interest due to emergency nature
        6,
        -- 6 months shorter term
        3000.00,
        30000.00
    ),
    (
        'Business Loan',
        'Capital financing for small business ventures and entrepreneurial activities',
        8.00,
        -- Higher 8% interest for business loans
        24,
        -- 2 years term
        10000.00,
        100000.00
    ),
    (
        'Personal Loan',
        'Multi-purpose loan for personal expenses and needs',
        7.50,
        -- 7.5% annual interest
        12,
        -- 1 year term
        5000.00,
        40000.00
    ),
    (
        'Agricultural Loan',
        'Financing for farming activities, equipment, and agricultural supplies',
        5.50,
        -- 5.5% interest to support agricultural sector
        18,
        -- 1.5 years term
        8000.00,
        75000.00
    );
-- Add sample overdue amortizations
INSERT INTO member_amortizations (
        member_id,
        type_id,
        principal_amount,
        monthly_amount,
        remaining_balance,
        start_date,
        end_date,
        status
    )
VALUES -- Test Member One - Educational Loan (30 days overdue)
    (
        16,
        1,
        25000.00,
        2291.67,
        20000.00,
        DATE_SUB(CURRENT_DATE, INTERVAL 2 MONTH),
        DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH),
        'active'
    ),
    -- Test Member Two - Personal Loan (45 days overdue)
    (
        17,
        4,
        30000.00,
        2750.00,
        25000.00,
        DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH),
        DATE_SUB(CURRENT_DATE, INTERVAL 45 DAY),
        'active'
    ),
    -- Test Member Three - Business Loan (60 days overdue)
    (
        18,
        3,
        50000.00,
        4583.33,
        45000.00,
        DATE_SUB(CURRENT_DATE, INTERVAL 4 MONTH),
        DATE_SUB(CURRENT_DATE, INTERVAL 2 MONTH),
        'active'
    ),
    -- Test Member Four - Agricultural Loan (90 days overdue)
    (
        19,
        5,
        40000.00,
        3666.67,
        38000.00,
        DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH),
        DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH),
        'active'
    ),
    -- Test Member Five - Calamity Loan (120 days overdue)
    (
        20,
        2,
        20000.00,
        1833.33,
        19000.00,
        DATE_SUB(CURRENT_DATE, INTERVAL 8 MONTH),
        DATE_SUB(CURRENT_DATE, INTERVAL 4 MONTH),
        'active'
    );
-- Monthly Balance Trend Sample Data
DELETE FROM member_transactions
WHERE created_at >= '2025-01-01';
-- Add sample transactions for Jan-Apr 2025 with unique reference numbers
INSERT INTO member_transactions (
        member_id,
        transaction_type,
        amount,
        previous_balance,
        new_balance,
        reference_number,
        notes,
        created_by,
        created_at
    ) WITH RECURSIVE months(month_num) AS (
        SELECT 1
        UNION ALL
        SELECT month_num + 1
        FROM months
        WHERE month_num < 4
    )
SELECT m.member_id,
    CASE
        WHEN RAND() > 0.3 THEN 'deposit'
        ELSE 'withdrawal'
    END as transaction_type,
    CASE
        WHEN RAND() > 0.3 THEN 1000 + FLOOR(RAND() * 9000) -- deposits 1000-10000
        ELSE 500 + FLOOR(RAND() * 2500) -- withdrawals 500-3000
    END as amount,
    m.current_balance as previous_balance,
    CASE
        WHEN RAND() > 0.3 THEN m.current_balance + (1000 + FLOOR(RAND() * 9000))
        ELSE m.current_balance - (500 + FLOOR(RAND() * 2500))
    END as new_balance,
    CONCAT(
        'TRX',
        DATE_FORMAT(
            DATE('2025-01-01') + INTERVAL mn.month_num -1 MONTH,
            '%Y%m'
        ),
        CASE
            WHEN RAND() > 0.3 THEN 'D'
            ELSE 'W'
        END,
        LPAD(m.member_id, 4, '0'),
        LPAD(
            ROW_NUMBER() OVER (
                PARTITION BY m.member_id
                ORDER BY mn.month_num
            ),
            3,
            '0'
        )
    ) as reference_number,
    'Sample transaction for trends',
    1,
    DATE('2025-01-01') + INTERVAL mn.month_num -1 MONTH + INTERVAL FLOOR(RAND() * 28) DAY
FROM members m
    CROSS JOIN months mn
WHERE m.membership_status = 'active';
-- Add sample daily transactions for the past week
INSERT INTO member_transactions (
        member_id,
        transaction_type,
        amount,
        previous_balance,
        new_balance,
        reference_number,
        notes,
        created_by,
        created_at
    ) WITH RECURSIVE days(day_offset) AS (
        SELECT 0
        UNION ALL
        SELECT day_offset + 1
        FROM days
        WHERE day_offset < 7
    )
SELECT m.member_id,
    CASE
        WHEN RAND() > 0.3 THEN 'deposit'
        ELSE 'withdrawal'
    END as transaction_type,
    CASE
        WHEN RAND() > 0.3 THEN 1000 + FLOOR(RAND() * 9000) -- deposits 1000-10000
        ELSE 500 + FLOOR(RAND() * 2500) -- withdrawals 500-3000
    END as amount,
    m.current_balance as previous_balance,
    CASE
        WHEN RAND() > 0.3 THEN m.current_balance + (1000 + FLOOR(RAND() * 9000))
        ELSE m.current_balance - (500 + FLOOR(RAND() * 2500))
    END as new_balance,
    CONCAT(
        'TRX',
        DATE_FORMAT(
            DATE_SUB(CURRENT_DATE, INTERVAL d.day_offset DAY),
            '%Y%m%d'
        ),
        CASE
            WHEN RAND() > 0.3 THEN 'D'
            ELSE 'W'
        END,
        LPAD(m.member_id, 4, '0'),
        LPAD(FLOOR(RAND() * 1000), 3, '0')
    ) as reference_number,
    'Daily transaction sample',
    1,
    DATE_SUB(CURRENT_DATE, INTERVAL d.day_offset DAY)
FROM members m
    CROSS JOIN days d
WHERE m.membership_status = 'active';