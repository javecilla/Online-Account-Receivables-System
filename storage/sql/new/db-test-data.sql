USE oarsmc_db;
-- Test Member Accounts for Overdue Scenarios
INSERT INTO accounts (
        role_id,
        account_uid,
        email,
        username,
        password,
        account_status
    )
VALUES (
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
    );
-- Test Members with Overdue Loans
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
-- Sample Overdue Amortizations
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
-- Sample Monthly Balance Trend Data (Jan-Apr 2025)
DELETE FROM member_transactions
WHERE created_at >= '2025-01-01';
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
-- Sample Daily Transactions for Past Week
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