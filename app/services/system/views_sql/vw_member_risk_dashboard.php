<?php

declare(strict_types=1);

require_once __DIR__ . '/vw_member_details.php';

function vw_member_risk_dashboard(): string
{
    /*
        # Member risk dashboard with loan default metrics
        # Aggregates data from vw_member_details (which includes vw_member_loan_metrics)
        CREATE VIEW vw_member_risk_dashboard AS
    */

    // Get the SQL for the underlying view dynamically
    $memberDetailsSql = vw_member_details();

    return "SELECT
        COUNT(*) as total_members,
        SUM(CASE WHEN defaulted_loans > 0 THEN 1 ELSE 0 END) as members_with_defaults,
        -- Ensure division by zero is handled if total_members is 0
        ROUND(
            IFNULL(
                SUM(CASE WHEN defaulted_loans > 0 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(*), 0),
                0
            ),
            2
        ) as default_member_percentage,
        SUM(CASE WHEN risk_level = 'High Risk' THEN 1 ELSE 0 END) as high_risk_members,
        SUM(CASE WHEN risk_level = 'Medium Risk' THEN 1 ELSE 0 END) as medium_risk_members,
        SUM(CASE WHEN risk_level = 'Low Risk' THEN 1 ELSE 0 END) as low_risk_members,
        SUM(CASE WHEN risk_level = 'No History' THEN 1 ELSE 0 END) as no_history_members,
        -- Calculate average default rate only among members with loans
        ROUND(
            IFNULL(
                AVG(CASE WHEN total_loans > 0 THEN default_rate ELSE NULL END), -- Use NULL to exclude members with no loans from AVG
                0 -- Default to 0 if no members have loans
            ),
            2
        ) as avg_default_rate_among_borrowers,
        MAX(defaulted_loans) as max_defaulted_loans,
        -- Count members who are currently 'closed' AND show defaulted loans in their metrics
        SUM(CASE WHEN membership_status = 'closed' AND defaulted_loans > 0 THEN 1 ELSE 0 END) as closed_with_defaults
    FROM ($memberDetailsSql) as member_details"; // Use the SQL from vw_member_details directly
}