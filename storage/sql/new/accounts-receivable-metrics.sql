-- Create views for accounts receivable metrics
-- View for Accounts Receivable Aging
CREATE OR REPLACE VIEW vw_accounts_receivable_aging AS
SELECT COUNT(DISTINCT m.member_id) as total_members,
    SUM(
        CASE
            WHEN ma.status = 'active'
            AND DATEDIFF(
                CURRENT_DATE,
                DATE_ADD(ap.payment_date, INTERVAL 1 MONTH)
            ) <= 0 THEN ma.remaining_balance
            ELSE 0
        END
    ) as current_receivables,
    SUM(
        CASE
            WHEN ma.status = 'active'
            AND DATEDIFF(
                CURRENT_DATE,
                DATE_ADD(ap.payment_date, INTERVAL 1 MONTH)
            ) BETWEEN 1 AND 30 THEN ma.remaining_balance
            ELSE 0
        END
    ) as days_1_30_overdue,
    SUM(
        CASE
            WHEN ma.status = 'active'
            AND DATEDIFF(
                CURRENT_DATE,
                DATE_ADD(ap.payment_date, INTERVAL 1 MONTH)
            ) BETWEEN 31 AND 60 THEN ma.remaining_balance
            ELSE 0
        END
    ) as days_31_60_overdue,
    SUM(
        CASE
            WHEN ma.status = 'active'
            AND DATEDIFF(
                CURRENT_DATE,
                DATE_ADD(ap.payment_date, INTERVAL 1 MONTH)
            ) > 60 THEN ma.remaining_balance
            ELSE 0
        END
    ) as days_60_plus_overdue
FROM members m
    LEFT JOIN member_amortizations ma ON m.member_id = ma.member_id
    LEFT JOIN (
        SELECT amortization_id,
            MAX(payment_date) as payment_date
        FROM amortization_payments
        GROUP BY amortization_id
    ) ap ON ma.amortization_id = ap.amortization_id;
-- View for Collection Efficiency Rate
CREATE OR REPLACE VIEW vw_collection_efficiency AS
SELECT ROUND(
        (
            SUM(ap.amount) / NULLIF(SUM(ma.monthly_amount), 0)
        ) * 100,
        2
    ) as collection_rate,
    COUNT(DISTINCT ma.amortization_id) as total_active_loans,
    SUM(ap.amount) as total_collected_amount,
    SUM(ma.monthly_amount) as total_expected_amount
FROM member_amortizations ma
    LEFT JOIN amortization_payments ap ON ma.amortization_id = ap.amortization_id
WHERE ma.status = 'active'
    AND ap.payment_date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY);
-- View for Delinquency Rate
CREATE OR REPLACE VIEW vw_delinquency_rate AS
SELECT ROUND(
        (
            COUNT(
                DISTINCT CASE
                    WHEN ma.status = 'defaulted' THEN ma.amortization_id
                    WHEN ma.status = 'active'
                    AND DATEDIFF(
                        CURRENT_DATE,
                        DATE_ADD(
                            COALESCE(last_payment.payment_date, ma.start_date),
                            INTERVAL 1 MONTH
                        )
                    ) > 30 THEN ma.amortization_id
                    ELSE NULL
                END
            ) * 100.0 / NULLIF(COUNT(DISTINCT ma.amortization_id), 0)
        ),
        2
    ) as delinquency_rate,
    COUNT(
        DISTINCT CASE
            WHEN ma.status = 'defaulted' THEN ma.amortization_id
            WHEN ma.status = 'active'
            AND DATEDIFF(
                CURRENT_DATE,
                DATE_ADD(
                    COALESCE(last_payment.payment_date, ma.start_date),
                    INTERVAL 1 MONTH
                )
            ) > 30 THEN ma.amortization_id
            ELSE NULL
        END
    ) as total_delinquent_loans,
    COUNT(DISTINCT ma.amortization_id) as total_loans,
    SUM(
        CASE
            WHEN ma.status = 'defaulted' THEN ma.remaining_balance
            WHEN ma.status = 'active'
            AND DATEDIFF(
                CURRENT_DATE,
                DATE_ADD(
                    COALESCE(last_payment.payment_date, ma.start_date),
                    INTERVAL 1 MONTH
                )
            ) > 30 THEN ma.remaining_balance
            ELSE 0
        END
    ) as total_delinquent_amount
FROM member_amortizations ma
    LEFT JOIN (
        SELECT amortization_id,
            MAX(payment_date) as payment_date
        FROM amortization_payments
        GROUP BY amortization_id
    ) last_payment ON ma.amortization_id = last_payment.amortization_id;