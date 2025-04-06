USE oarsmc_db;
-- Sample Fee Transactions
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
    )
SELECT m.member_id,
    'fee' as transaction_type,
    CASE
        WHEN ROW_NUMBER() OVER (
            PARTITION BY m.member_id
            ORDER BY m.member_id
        ) = 1 THEN 500.00 -- Membership Fee
        WHEN ROW_NUMBER() OVER (
            PARTITION BY m.member_id
            ORDER BY m.member_id
        ) = 2 THEN 250.00 -- Processing Fee
        ELSE 100.00 -- Late Payment Fee
    END as amount,
    m.current_balance as previous_balance,
    m.current_balance - CASE
        WHEN ROW_NUMBER() OVER (
            PARTITION BY m.member_id
            ORDER BY m.member_id
        ) = 1 THEN 500.00
        WHEN ROW_NUMBER() OVER (
            PARTITION BY m.member_id
            ORDER BY m.member_id
        ) = 2 THEN 250.00
        ELSE 100.00
    END as new_balance,
    CONCAT(
        'FEE',
        DATE_FORMAT(
            DATE_SUB(CURRENT_DATE, INTERVAL FLOOR(RAND() * 30) DAY),
            '%Y%m%d'
        ),
        LPAD(m.member_id, 4, '0'),
        LPAD(
            ROW_NUMBER() OVER (
                PARTITION BY m.member_id
                ORDER BY m.member_id
            ),
            3,
            '0'
        )
    ) as reference_number,
    CASE
        WHEN ROW_NUMBER() OVER (
            PARTITION BY m.member_id
            ORDER BY m.member_id
        ) = 1 THEN 'Annual Membership Fee'
        WHEN ROW_NUMBER() OVER (
            PARTITION BY m.member_id
            ORDER BY m.member_id
        ) = 2 THEN 'Loan Processing Fee'
        ELSE 'Late Payment Penalty Fee'
    END as notes,
    1 as created_by,
    DATE_SUB(CURRENT_DATE, INTERVAL FLOOR(RAND() * 30) DAY) as created_at
FROM members m
    CROSS JOIN (
        SELECT 1
        UNION
        SELECT 2
        UNION
        SELECT 3
    ) AS nums
WHERE m.membership_status = 'active';
-- Sample Interest Transactions
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
    )
SELECT ma.member_id,
    'interest' as transaction_type,
    ROUND(ma.principal_amount * (0.12 / 12), 2) as amount,
    -- 12% annual interest rate
    m.current_balance as previous_balance,
    m.current_balance + ROUND(ma.principal_amount * (0.12 / 12), 2) as new_balance,
    CONCAT(
        'INT',
        DATE_FORMAT(
            DATE_SUB(CURRENT_DATE, INTERVAL FLOOR(RAND() * 90) DAY),
            '%Y%m%d'
        ),
        LPAD(ma.member_id, 4, '0'),
        LPAD(
            ROW_NUMBER() OVER (
                PARTITION BY ma.member_id
                ORDER BY ma.member_id
            ),
            3,
            '0'
        )
    ) as reference_number,
    CONCAT(
        'Monthly Interest for ',
        CASE
            ma.type_id
            WHEN 1 THEN 'Educational'
            WHEN 2 THEN 'Calamity'
            WHEN 3 THEN 'Business'
            WHEN 4 THEN 'Personal'
            WHEN 5 THEN 'Agricultural'
        END,
        ' Loan'
    ) as notes,
    1 as created_by,
    DATE_SUB(CURRENT_DATE, INTERVAL FLOOR(RAND() * 90) DAY) as created_at
FROM member_amortizations ma
    JOIN members m ON m.member_id = ma.member_id
WHERE ma.status = 'active';