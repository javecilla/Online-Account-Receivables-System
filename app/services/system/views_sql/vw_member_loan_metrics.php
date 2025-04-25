<?php

declare(strict_types=1);

/*
- High Risk: A member is classified as 'High Risk' if either of these conditions is true:

- They have two or more loans with status = 'defaulted' .
- They have two or more loans that are both status = 'overdue' AND more than 90 days past their end_date .
- Medium Risk: A member is classified as 'Medium Risk' if:

- They have at least one loan with status = 'overdue' (regardless of how long it's overdue).
- AND they do not meet the criteria for 'High Risk' (e.g., they might have only one overdue loan, or one defaulted loan but no overdue loans > 90 days).
- No History: A member is classified as 'No History' if:

- They have zero loan records in the member_amortizations table.
- Low Risk: A member falls into 'Low Risk' if none of the above conditions (High Risk, Medium Risk, No History) are met. This includes scenarios like:

- All their loans are 'paid' or 'pending' (and not overdue).
- They have exactly one loan with status = 'defaulted' but no overdue loans.
- They have exactly one loan that is status = 'overdue' and more than 90 days past due, but no other overdue or defaulted loans.
*/
function vw_member_loan_metrics(): string
{
    /*
        # Member loan metrics including defaulted loans count
        CREATE VIEW vw_member_loan_metrics AS
    */
    return "SELECT
        m.member_id,
        COUNT(ma.amortization_id) AS total_loans,
        SUM(CASE WHEN ma.status = 'overdue' THEN 1 ELSE 0 END) AS overdue_loans,
        SUM(CASE WHEN ma.status = 'paid' THEN 1 ELSE 0 END) AS paid_loans,
        SUM(CASE WHEN ma.status = 'pending' THEN 1 ELSE 0 END) AS pending_loans,
        SUM(CASE WHEN ma.status = 'defaulted' THEN 1 ELSE 0 END) AS defaulted_loans,
        -- Overdue Rate (considers 'overdue' or 'pending' past end_date)
        ROUND(
            IFNULL(
                SUM(CASE WHEN ma.status = 'overdue' OR (CURRENT_DATE > ma.end_date AND ma.status = 'pending') THEN 1 ELSE 0 END) * 100.0 /
                NULLIF(COUNT(ma.amortization_id), 0),
                0
            ),
            2
        ) AS overdue_rate,
        -- Revised Default Rate: Includes 'defaulted' status OR 'overdue' > 90 days
        ROUND(
            IFNULL(
                SUM(CASE WHEN ma.status = 'defaulted' OR (ma.status = 'overdue' AND DATEDIFF(CURRENT_DATE, ma.end_date) > 90) THEN 1 ELSE 0 END) * 100.0 /
                NULLIF(COUNT(ma.amortization_id), 0),
                0
            ),
            2
        ) AS default_rate,
        -- Revised Risk Level: Incorporates defaulted loans and overdue loans
        CASE
            -- Revised High Risk: 1+ defaulted loan OR 1+ overdue loan > 90 days
            WHEN SUM(CASE WHEN ma.status = 'defaulted' THEN 1 ELSE 0 END) > 0 -- Changed from > 1
                 OR SUM(CASE WHEN ma.status = 'overdue' AND DATEDIFF(CURRENT_DATE, ma.end_date) > 90 THEN 1 ELSE 0 END) > 0 THEN 'High Risk' -- Changed from > 1
            -- Medium Risk: 1+ overdue loan (any duration, but not meeting High Risk criteria)
            WHEN SUM(CASE WHEN ma.status = 'overdue' THEN 1 ELSE 0 END) > 0 THEN 'Medium Risk'
            -- No History: No loans at all
            WHEN COUNT(ma.amortization_id) = 0 THEN 'No History'
            -- Low Risk: Otherwise
            ELSE 'Low Risk'
        END AS risk_level
    FROM members m
        LEFT JOIN member_amortizations ma ON m.member_id = ma.member_id
    GROUP BY m.member_id";
}