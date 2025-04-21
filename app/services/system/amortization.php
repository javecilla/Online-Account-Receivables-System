<?php

declare(strict_types=1);

/**
 * TODO: Amortization Management Functions
 */

function create_amortization(array $data): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        // $type = get_amortization_type($data['type_id']);
        // $interest_rate = $type['data']['interest_rate'];

        #calculate amortization details
        // $principal = $data['amount'];
        // $term_months = $data['term_months'];

        #calculate total interest for the loan period
        //$total_interest = $principal * (($interest_rate / 100) * ($term_months / 12));

        #Calculate total amount to be repaid (this will be the initial remaining_balance)
        //$total_repayment = $principal + $total_interest;

        #calculate fixed monthly payment
        //$monthly_amount = $total_repayment / $term_months;

        // $start_date = $data['start_date'];
        // $end_date = date('Y-m-d', strtotime($start_date . " +{$term_months} months"));

        $sql = "INSERT INTO member_amortizations (
            member_id, `type_id`, principal_amount, monthly_amount,
            remaining_balance, `start_date`, end_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'iidddss',
            $data['member_id'],
            $data['type_id'],
            $data['principal_amount'],
            $data['monthly_amount'],
            $data['remaining_balance'],
            $data['start_date'],
            $data['end_date']
        );
        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to create amortization'];
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => 'Amortization created successfully',
            // 'data' => [
            //     'principal_amount' => $principal,
            //     'total_interest' => $total_interest,
            //     'total_repayment' => $total_repayment,
            //     'monthly_amount' => $monthly_amount,
            //     'term_months' => $term_months,
            //     'start_date' => $start_date,
            //     'end_date' => $end_date
            // ]
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error creating amortization: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error: {$e->getMessage()}"];
    }
}

function update_amortization(int $amortization_id, array $data): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $sql = "UPDATE member_amortizations
                SET `type_id` =?, principal_amount =?, monthly_amount =?, remaining_balance =?,
                    `start_date` =?, end_date =?
                WHERE amortization_id =? LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'idddssi',
            $data['type_id'],
            $data['principal_amount'],
            $data['monthly_amount'],
            $data['remaining_balance'],
            $data['start_date'],
            $data['end_date'],
            $amortization_id
        );
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false,'message' => 'Failed to update amortization'];
        }

        $conn->commit();
        return ['success' => true,'message' => 'Amortization updated successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating amortization: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error updating amortization: {$e->getMessage()}"];
    }
}

function get_amortization(int $amortization_id): array
{
    try {
        $conn = open_connection();

        $sql = vw_amortization_details() . " WHERE ma.amortization_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $amortization_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => "Amortization with id {$amortization_id} is not found.", 'status' => 404];
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $result->fetch_assoc()];
    } catch (Exception $e) {
        log_error("Error fetching amortization: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_amortizations_by_status(array $statuses): array
{
    try {
        $conn = open_connection();

        $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
        $sql = vw_amortization_details() . " WHERE ma.status IN ({$placeholders}) AND ma.approval = 'approved' ORDER BY ma.updated_at DESC";
        $stmt = $conn->prepare($sql);

        $types = str_repeat('s', count($statuses));
        $stmt->bind_param($types, ...$statuses);
        $stmt->execute();
        $result = $stmt->get_result();
        $amortizations = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $amortizations];
    } catch (Exception $e) {
        log_error("Error fetching amortization by status: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_amortizations_by_criteria(array $statuses, array $loan_types): array
{
    try {
        $conn = open_connection();

        $status_placeholders = str_repeat('?,', count($statuses) - 1) . '?';
        $loan_type_placeholders = str_repeat('?,', count($loan_types) - 1) . '?';
        $sql = vw_amortization_details() . " WHERE ma.status IN ({$status_placeholders}) AND at.type_name IN ({$loan_type_placeholders}) AND ma.approval = 'approved' ORDER BY ma.updated_at DESC";
        $stmt = $conn->prepare($sql);
        $params = array_merge($statuses, $loan_types);
        $types = str_repeat('s', count($statuses)) . str_repeat('s', count($loan_types));
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $result = $stmt->get_result();
        $amortizations = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $amortizations];
    } catch (Exception $e) {
        log_error("Error fetching amortization by criteria: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_member_approved_amortizations(int $member_id, array $statuses): array
{
    try {
        $conn = open_connection();

        $placeholders = str_repeat('?,', count($statuses) - 1) . '?';
        $sql = vw_amortization_details() . " WHERE m.member_id = ? AND ma.status IN ({$placeholders}) AND ma.approval = 'approved' ORDER BY ma.updated_at DESC";
        $stmt = $conn->prepare($sql);

        $types = 'i' . str_repeat('s', count($statuses));
        $stmt->bind_param($types, $member_id, ...$statuses);
        $stmt->execute();
        $result = $stmt->get_result();
        $amortizations = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $amortizations];
    } catch (Exception $e) {
        log_error("Error fetching member approved amortizations: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_member_request_amortizations(int $member_id, $approvals): array {
    try {
        $conn = open_connection();

        $placeholders = str_repeat('?,', count($approvals) - 1) . '?';
        $sql = vw_amortization_details() . " WHERE m.member_id = ? AND ma.approval IN ({$placeholders}) AND ma.status IS NULL ORDER BY ma.updated_at DESC";
        $stmt = $conn->prepare($sql);

        $types = 'i' . str_repeat('s', count($approvals));
        $stmt->bind_param($types, $member_id, ...$approvals);
        $stmt->execute();
        $result = $stmt->get_result();
        $amortizations = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $amortizations];
    } catch (Exception $e) {
        log_error("Error fetching member request amortizations: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_amortizations_by_approval(array $approvals): array
{
    try {
        $conn = open_connection();

        $placeholders = str_repeat('?,', count($approvals) - 1) . '?';
        $sql = vw_amortization_details() . " WHERE ma.approval IN ({$placeholders}) AND ma.status IS NULL";
        $stmt = $conn->prepare($sql);

        $types = str_repeat('s', count($approvals));
        $stmt->bind_param($types, ...$approvals);
        $stmt->execute();

        $result = $stmt->get_result();
        $amortizations = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $amortizations];
    } catch (Exception $e) {
        log_error("Error fetching amortization by approval: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_member_amortizations(int $member_id, int $page = 1, int $per_page = 10, ?string $status = null): array
{
    try {
        $conn = open_connection();

        $where_clause = "m.member_id = ?";
        $types = "i";
        $params = [$member_id];

        if ($status !== null) {
            $where_clause .= " AND `status` = ?";
            $types .= "s";
            $params[] = $status;
        }

        $base_sql = vw_amortization_details();
        $sql = $base_sql . " WHERE {$where_clause} ORDER BY ma.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $amortizations = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return [
            'success' => true,
            'data' => $amortizations
        ];
    } catch (Exception $e) {
        log_error("Error fetching amortizations: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function create_amortization_payment(array $data): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $sql = "INSERT INTO amortization_payments (
            amortization_id, payment_method, amount, payment_date, reference_number, notes, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'isdsssi',
            $data['amortization_id'],
            $data['payment_method'],
            $data['amount'],
            $data['payment_date'],
            $data['reference_number'],
            $data['notes'],
            $data['created_by']
        );
        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to record payment'];
        }
        $conn->commit();
        $payment_id = $stmt->insert_id;
        return ['success' => true,'message' => 'Payment recorded successfully', 'payment_id' => $payment_id];
    } catch(Exception $e) {
        $conn->rollback();
        log_error("Error creating amortization payment: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function update_amortization_remaining_balance(int $amortization_id, float $new_remaining): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $sql = "UPDATE member_amortizations SET remaining_balance = ? WHERE amortization_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('di', $new_remaining, $amortization_id);
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update remaining balance'];
        }

        $conn->commit();
        return ['success' => true,'message' => 'Remaining balance updated successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating member amortization remaining balance: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function process_amortization_payment(array $data): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $data['reference_number'] = 'AMT' . date('Ymd') . generate_random_number(4);
        $data['created_by'] = $_SESSION['account_id'] ?? DEFAULT_ADMIN_ID;

        //get member details for transaction recording
        $m = get_member((int)$data['member_id']);
        if(!$m['success']) return $m;
        $member = $m['data'];

        //get amortization details for transaction recording
        $a = get_amortization($data['amortization_id']);
        if(!$a['success']) return $a;
        $amort = $a['data'];

        //extra layer check lang for amortization status, if already paid completed na then no need to process
        if ($amort['status'] === AMORTIZATION_PAID) {
            return ['success' => false, 'message' => 'Unable to process payment. You are already paid completed this amortization payment', 'status' => 403];
        }

        // Calculate payment allocation and potential credit
        $payment_amount = $data['final_amount_payment'];
        $credit_amount = $data['used_credit_balance'];

        //handle payment that uses their credit balance in loan payment
        if($data['is_use_credit'] && $credit_amount > 0) {
            //update member credit balance
            $new_credit_balance = $member['credit_balance'] - $credit_amount;
            $updated_credit = update_member_credit_balance($member['member_id'], $new_credit_balance);
            if (!$updated_credit['success']) {
                $conn->rollback();
                return $updated_credit;
            }

            //record transaction
            $credit_recorded = record_transaction($member['member_id'], [
                'transaction_type' => WITHDRAWAL,
                'amount' => $credit_amount,
                'previous_balance' => $member['credit_balance'],
                'new_balance' => $new_credit_balance,
                'notes' => "Payment for {$amort['type_name']} with used credit balance (Ref: {$data['reference_number']})",
                'created_by' => $data['created_by']
            ]);
            if (!$credit_recorded['success']) {
                $conn->rollback();
                return $credit_recorded;
            }
        }
        
        //if yung payment is more than dun sa remaining balance and is_create_credit is true
        if ($payment_amount > $amort['remaining_balance'] && $data['is_create_credit']) {
            $credit_amount = $payment_amount - $amort['remaining_balance'];
            $payment_amount = $amort['remaining_balance'];

            //update member credit balance
            $new_credit_balance = $member['credit_balance'] + $credit_amount;
            $updated_credit = update_member_credit_balance($member['member_id'], $new_credit_balance);
            if (!$updated_credit['success']) {
                $conn->rollback();
                return $updated_credit;
            }

            //record transaction
            $credit_recorded = record_transaction($member['member_id'], [
                'transaction_type' => DEPOSIT,
                'amount' => $credit_amount,
                'previous_balance' => $member['credit_balance'],
                'new_balance' => $new_credit_balance,
                'notes' => "Credit from excess payment for {$amort['type_name']} (Ref: {$data['reference_number']})",
                'created_by' => $data['created_by']
            ]);
            if (!$credit_recorded['success']) {
                $conn->rollback();
                return $credit_recorded;
            }
        }

        //calculate member new balance
        $new_balance = $member['current_balance'] + $payment_amount;
        //update member current balance
        $updated_balance = update_member_current_balance($member['member_id'], $new_balance);
        if (!$updated_balance['success']) {
            $conn->rollback();
            return $updated_balance;
        }

        //update remaining balance
        $new_remaining =  $amort['remaining_balance'] - $payment_amount;
        $updated_rb = update_amortization_remaining_balance($data['amortization_id'], $new_remaining);
        if (!$updated_rb['success']) {
            $conn->rollback();
            return $updated_rb;
        }

        //if remaining balance is 0 or less, mark amortization as completed
        if ($new_remaining <= 0) {
            $updated_status = update_amortization_status($data['amortization_id'], AMORTIZATION_PAID);
            if (!$updated_status['success']) {
                $conn->rollback();
                return $updated_status;
            }
        }

        // create payment
        $data['amount'] = $payment_amount;
        $payment_created = create_amortization_payment($data);
        if(!$payment_created['success']) {
            $conn->rollback();
            return $payment_created;
        }

        //record main payment transaction
        $payment_recorded = record_transaction($member['member_id'], [
            'transaction_type' => DEPOSIT,
            'amount' => $payment_amount,
            'previous_balance' => $member['current_balance'],
            'new_balance' => $new_balance,
            'notes' => "Amortization payment for {$amort['type_name']} (Ref: {$data['reference_number']})",
            'created_by' => $data['created_by']
        ]);
        if (!$payment_recorded['success']) {
            $conn->rollback();
            return $payment_recorded;
        }
        
        $conn->commit();
        //return ['success' => true,'message' => '[TEST]: Payment processed successfully'];
        return [
            'success' => true,
            'message' => 'Payment processed successfully',
            'data' => [
                'reference_number' => $data['reference_number'],
                'transaction_reference' => $payment_recorded['data']['reference_number'],
                'remaining_balance' => $new_remaining,
                'new_balance' => $new_balance,
                'credit_amount' => $credit_amount
            ]
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error processing amortization: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error processing amortization: {$e->getMessage()}"];
    }
}

function update_amortization_status(int $amortization_id, string $new_status): array
{
    try {
        $conn = open_connection();

        $conn->begin_transaction();

        $sql = "UPDATE member_amortizations 
                SET `status` = ?, updated_at = CURRENT_TIMESTAMP
                WHERE amortization_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $new_status, $amortization_id);
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update amortization status'];
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => "Amortization status updated to {$new_status}",
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating amortization status: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function update_amortization_approval(int $amortization_id, string $new_approval): array
{
    try {
        $conn = open_connection();

        $conn->begin_transaction();

        $sql = "UPDATE member_amortizations 
                SET approval = ?, updated_at = CURRENT_TIMESTAMP
                WHERE amortization_id = ? LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $new_approval, $amortization_id);
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update amortization approval'];
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => "Amortization approval updated to {$new_approval}",
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating amortization approval: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function delete_amortization(int $amortization_id): array
{
    try {
        $conn = open_connection();

        $conn->begin_transaction();

        $sql = "DELETE FROM member_amortizations WHERE amortization_id = ? /*AND `status` IS NULL AND approval = 'rejected'*/ LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $amortization_id);
        $deleted = $stmt->execute();

        if (!$deleted) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to delete amortization', 'status' => 500];
        }

        $conn->commit();
        return ['success' => true, 'message' => 'Amortization deleted successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error deleting amortization: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_amortization_payments(): array
{
    try {
        $conn = open_connection();

        $sql = vw_amortization_payments_details();
        $result = $conn->query($sql);
        $payments = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $payments];
    } catch (Exception $e) {
        log_error("Error fetching amortization payments: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_member_amortization_payments(int $amortization_id): array
{
    try {
        $conn = open_connection();

        $sql = vw_amortization_payments_details(). " WHERE ap.amortization_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $amortization_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $payments = $result->num_rows > 0? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true,'message' => 'Retrieved successfully', 'data' => $payments];
    } catch (Exception $e) {
        log_error("Error fetching amortization payments: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Database error: {$e->getMessage()}"];
    }
}

/**
 * Helpers
 */
function check_active_amortizations(int $member_id): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT COUNT(*) as active_count 
                FROM member_amortizations 
                WHERE member_id = ? AND `status` IN ('pending', 'overdue')"; //active

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['active_count'];

        return [
            'success' => true,
            'has_active' => $count > 0,
            'active_count' => $count
        ];
    } catch (Exception $e) {
        log_error("Error checking active amortizations: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

/**
 * Reports and Analytics
 */

function get_daily_transaction_stats(?string $start_date = null, ?string $end_date = null): array
{
    try {
        $conn = open_connection();

        $base_sql = vw_daily_transaction_summary();

        $where_clause = "";
        $params = [];
        $types = "";

        if ($start_date && $end_date) {
            $where_clause = " WHERE DATE(created_at) BETWEEN ? AND ?";
            $params = [$start_date, $end_date];
            $types = "ss";
        }

        $sql = $base_sql . $where_clause . "
        GROUP BY DATE(created_at), transaction_type
        ORDER BY transaction_date DESC";

        if ($params) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching daily transaction stats: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_monthly_balance_trends(?string $year = null): array
{
    try {
        $conn = open_connection();

        $base_sql = vw_monthly_balance_trends();

        $where_clause = "";
        $params = [];
        $types = "";

        if ($year) {
            $where_clause = " WHERE DATE_FORMAT(mt.created_at, '%Y-%m') LIKE ?";
            $params = [$year . '%'];
            $types = "s";
        }

        $sql = $base_sql . $where_clause . "
        GROUP BY DATE_FORMAT(mt.created_at, '%Y-%m'), mt.type_name
        ORDER BY month_year DESC, account_type";

        if ($params) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching monthly balance trends: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_loan_performance_metrics(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_loan_performance_analytics());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching loan performance metrics: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_payment_collection_efficiency(?int $year = null, ?int $month = null): array
{
    try {
        $conn = open_connection();

        $where_clause = "";
        $params = [];
        $types = "";

        if ($year && $month) {
            $where_clause = "WHERE year = ? AND month = ?";
            $params = [$year, $month];
            $types = "ii";
        } else if ($year) {
            $where_clause = "WHERE year = ?";
            $params = [$year];
            $types = "i";
        }

        $sql = vw_payment_collection_efficiency() . " {$where_clause}";

        if ($params) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching payment collection efficiency: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_member_growth_stats(?string $start_date = null, ?string $end_date = null): array
{
    try {
        $conn = open_connection();

        $where_clause = "";
        $params = [];
        $types = "";

        if ($start_date && $end_date) {
            $where_clause = "WHERE month_year BETWEEN ? AND ?";
            $params = [$start_date, $end_date];
            $types = "ss";
        }

        $sql = vw_member_growth_analysis() . " {$where_clause} ORDER BY month_year DESC";

        if ($params) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching member growth stats: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_risk_assessment_metrics(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_risk_assessment_dashboard());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching risk assessment metrics: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_amortization_summary_stats(?int $member_id = null): array
{
    try {
        $conn = open_connection();

        $where_clause = $member_id ? "WHERE member_id = ?" : "";
        $sql = vw_amortization_payment_summary() . " {$where_clause}";

        if ($member_id) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $member_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching amortization summary stats: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_dashboard_metrics(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_dashboard_metrics());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching dashboard summary: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_monthly_receivables_trend(?string $start_date = null, ?string $end_date = null): array
{
    try {
        $conn = open_connection();

        $where_clause = "";
        $params = [];
        $types = "";

        if ($start_date && $end_date) {
            $where_clause = "WHERE month_date BETWEEN ? AND ?";
            $params = [$start_date, $end_date];
            $types = "ss";
        }

        $sql = vw_monthly_receivables_trend() . " {$where_clause} ORDER BY month_date DESC";

        if ($params) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching monthly receivables trend: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_monthly_overdue_metrics(?string $start_date = null, ?string $end_date = null): array
{
    try {
        $conn = open_connection();

        $where_clause = "";
        $params = [];
        $types = "";

        if ($start_date && $end_date) {
            $where_clause = "WHERE month_date BETWEEN ? AND ?";
            $params = [$start_date, $end_date];
            $types = "ss";
        }

        $sql = vw_monthly_overdue_metrics() . " {$where_clause} ORDER BY month_date DESC";

        if ($params) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($sql);
        }

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error fetching monthly overdue metrics: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_annual_financial_summary_income_from_payments(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_annual_financial_summary_income_from_payments());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_annual_financial_summary_transactions(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_annual_financial_summary_transactions());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_monthly_financial_summary_income_from_payments(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_monthly_financial_summary_income_from_payments());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_monthly_financial_summary_transactions(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_monthly_financial_summary_transactions());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_outstanding_receivables_by_member(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_outstanding_receivables_by_member());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_payment_histories_by_member(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_payment_histories_by_member());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_payment_trends_monthly(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_payment_trends_monthly());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_quarterly_financial_summary_income_from_payments(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_quarterly_financial_summary_income_from_payments());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}

function get_quarterly_financial_summary_transactions(): array
{
    try {
        $conn = open_connection();
        $result = $conn->query(vw_quarterly_financial_summary_transactions());

        return [
            'success' => true,
            'data' => $result->fetch_all(MYSQLI_ASSOC)
        ];
    } catch (Exception $e) {
        log_error("Error: {$e->getMessage()}");
        return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
    }
}