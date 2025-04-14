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

        $type = get_amortization_type($data['type_id']);
        $interest_rate = $type['data']['interest_rate'];

        //calculate amortization details
        $principal = $data['amount'];
        $term_months = $data['term_months'];

        //calculate total interest for the loan period
        $total_interest = $principal * (($interest_rate / 100) * ($term_months / 12));

        //Calculate total amount to be repaid (this will be the initial remaining_balance)
        $total_repayment = $principal + $total_interest;

        //clculate fixed monthly payment
        $monthly_amount = $total_repayment / $term_months;

        $start_date = $data['start_date'];
        $end_date = date('Y-m-d', strtotime($start_date . " +{$term_months} months"));

        $sql = "INSERT INTO member_amortizations (
            member_id, `type_id`, principal_amount, monthly_amount,
            remaining_balance, `start_date`, end_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'iidddss',
            $data['member_id'],
            $data['type_id'],
            $principal,
            $monthly_amount,
            $total_repayment, //initial remaining_balance is the total amount to be repaid
            $start_date,
            $end_date
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
            'data' => [
                'principal_amount' => $principal,
                'total_interest' => $total_interest,
                'total_repayment' => $total_repayment,
                'monthly_amount' => $monthly_amount,
                'term_months' => $term_months,
                'start_date' => $start_date,
                'end_date' => $end_date
            ]
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error creating amortization: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error: {$e->getMessage()}"];
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

function process_amortization_payment(array $data): array
{
    try {
        $conn = open_connection();
        // TODO: must the logged-in user's account_id as created_by
        $created_by = $_SESSION['account_id'] ?? DEFAULT_ADMIN_ID;
        $reference_number = 'AMT' . date('Ymd') . generate_random_number(4);
        $conn->begin_transaction();

        //get amortization details for transaction recording
        $amortization = get_amortization($data['amortization_id']);
        $amort = $amortization['data'];
        //check for amortization status, if completed then hindi ipa process
        if ($amort['status'] === AMORTIZATION_COMPLETED) {
            return ['success' => false, 'message' => 'Unable to proccess payment. You are already completed this amortization payment', 'status' => 403];
        }

        // Insert payment record
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
            $reference_number,
            $data['notes'],
            $created_by
        );
        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to record payment'];
        }

        // Update remaining balance na need niya for repaid 
        $new_remaining = $amort['remaining_balance'] - $data['amount'];
        $rb_sql = "UPDATE member_amortizations  SET remaining_balance = ? 
                   WHERE amortization_id = ? LIMIT 1";
        $rb_stmt = $conn->prepare($rb_sql);
        $rb_stmt->bind_param('di', $new_remaining, $data['amortization_id']);
        $rb_updated = $rb_stmt->execute();
        if (!$rb_updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update remaining balance'];
        }

        //calculate member new balance after deposit
        $current_balance = $amort['current_balance'];
        $new_balance = $current_balance + $data['amount'];

        // Record transaction
        $transaction_data = [
            'transaction_type' => DEPOSIT,
            'amount' => $data['amount'],
            'previous_balance' => $current_balance,
            'new_balance' => $new_balance,
            'notes' => "Amortization payment for {$amort['type_name']} (Ref: {$reference_number})",
            'created_by' => $created_by
        ];
        $recorded = record_transaction($amort['member_id'], $transaction_data);
        if (!$recorded['success']) {
            $conn->rollback();
            return $recorded;
        }

        // Update member's current balance
        $cb_sql = "UPDATE members SET current_balance = ? WHERE member_id = ? LIMIT 1";
        $cb_stmt = $conn->prepare($cb_sql);
        $cb_stmt->bind_param('di', $new_balance, $amort['member_id']);
        $cb_updated = $cb_stmt->execute();
        if (!$cb_updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update member balance'];
        }

        //if remaining balance is 0 or less, mark amortization as completed
        if ($new_remaining <= 0) {
            // $status_sql = "UPDATE member_amortizations 
            //               SET `status` = 'completed' 
            //               WHERE amortization_id = ?";
            // $status_stmt = $conn->prepare($status_sql);
            // $status_stmt->bind_param('i', $data['amortization_id']);
            // $status_stmt->execute();
            $s_updated = update_amortization_status(
                $data['amortization_id'],
                AMORTIZATION_COMPLETED
            );
            if (!$s_updated['success']) {
                $conn->rollback();
                return $s_updated;
            }
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => 'Payment processed successfully',
            'data' => [
                'reference_number' => $reference_number,
                'transaction_reference' => $recorded['data']['reference_number'],
                'remaining_balance' => $new_remaining,
                'new_balance' => $new_balance
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

        $sql = "DELETE FROM member_amortizations WHERE amortization_id = ? AND `status` IS NULL AND approval = 'rejected' LIMIT 1";
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
                WHERE member_id = ? AND `status` = 'active'";

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
