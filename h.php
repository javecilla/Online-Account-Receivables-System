<?php

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/system.php';

$conn = open_connection();
var_dump($conn);

// $password = hash_password('member');
// var_dump($password);

// $sql = "UPDATE `accounts` SET `password` = '{$password}', `email` = 'jeromesavc@gmail.com' 
//   WHERE `account_id` = 1 LIMIT 1";

// $sql = "UPDATE `accounts` SET `password` = '{$password}', `username` = 'member', `email` = 'member@gmail.com'
//   WHERE `account_id` = 18 LIMIT 1";
// $stmt = $conn->prepare($sql);
// $result = $stmt->execute();
// var_dump($result);

//calculate amortization details
$principal = 25000;
$term_months = 12;
$interest_rate = 6.00;
$start_date = '2025-04-12';

$end_date = date('Y-m-d', strtotime($start_date . " +{$term_months} months"));

//calculate total interest for the loan period
$total_interest = $principal * (($interest_rate / 100) * ($term_months / 12));

//Calculate total amount to be repaid (this will be the initial remaining_balance)
$total_repayment = $principal + $total_interest;

//clculate fixed monthly payment
$monthly_amount = $total_repayment / $term_months;
//format to 2 decimal places
$monthly_amount = number_format($monthly_amount, 2);
$total_repayment = number_format($total_repayment, 2);

echo "principal_amount: {$principal}\n";
echo "monthly_amount: {$monthly_amount}\n";
echo "total_interest: {$total_interest}\n";
echo "remaining_balance: {$total_repayment}\n";
echo "end_date: {$end_date}\n";
