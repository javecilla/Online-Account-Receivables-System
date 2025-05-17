<?php

declare(strict_types=1);

/**
 * Calculate the maturity date for a deposit account based on its type
 * 
 * @param string $opened_date The date when the account was opened (Y-m-d format)
 * @param int $type_id The type ID of the account (2 for Time Deposit, 3 for Fixed Deposit)
 * @return string The maturity date in Y-m-d format
 */
function calculate_maturity_date(string $opened_date, int $type_id): string
{
    try {
        // Convert opened_date to DateTime object
        $date = new DateTime($opened_date);
        
        // Define maturity periods based on account type
        // Time Deposit (type_id = 2) typically has shorter term (e.g., 30-90 days)
        // Fixed Deposit (type_id = 3) typically has longer term (e.g., 1-5 years)
        switch ($type_id) {
            case 2: // Time Deposit
                // Add 3 months (90 days) for Time Deposit
                $date->add(new DateInterval('P90D'));
                break;
            case 3: // Fixed Deposit
                // Add 1 year for Fixed Deposit
                $date->add(new DateInterval('P1Y'));
                break;
            default:
                // For unsupported account types, return the original date
                break;
        }
        
        // Return the calculated maturity date in Y-m-d format
        return $date->format('Y-m-d');
    } catch (Exception $e) {
        log_error("Error calculating maturity date: {$e->getMessage()}");
        // Return the original date if there's an error
        return $opened_date;
    }
}

/**
 * Check if a deposit account has reached maturity
 * 
 * @param string $opened_date The date when the account was opened (Y-m-d format)
 * @param int $type_id The type ID of the account
 * @return array Information about maturity status
 */
function check_maturity_status(string $opened_date, int $type_id): array
{
    try {
        $maturity_date = calculate_maturity_date($opened_date, $type_id);
        $current_date = date('Y-m-d');
        
        $is_mature = strtotime($current_date) >= strtotime($maturity_date);
        
        return [
            'success' => true,
            'data' => [
                'is_mature' => $is_mature,
                'maturity_date' => $maturity_date,
                'current_date' => $current_date,
                'days_until_maturity' => $is_mature ? 0 : ceil((strtotime($maturity_date) - strtotime($current_date)) / (60 * 60 * 24))
            ]
        ];
    } catch (Exception $e) {
        log_error("Error checking maturity status: {$e->getMessage()}");
        return ['success' => false, 'message' => "Error checking maturity status: {$e->getMessage()}"];
    }
}