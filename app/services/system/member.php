<?php

declare(strict_types=1);

function create_member(array $data): array
{
    try {
        $conn = open_connection();

        $member_uid = 'M' . generate_random_number(6);
        $opened_date = date('Y-m-d');

        $conn->begin_transaction();

        $sql = "INSERT INTO members (
            account_id,
            `type_id`,
            member_uid,
            first_name,
            middle_name,
            last_name,
            contact_number,
            house_address,
            barangay,
            municipality,
            province,
            region,
            opened_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'iisssssssssss',
            $data['account_id'],
            $data['type_id'],
            $member_uid,
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['contact_number'],
            $data['house_address'],
            $data['barangay'],
            $data['municipality'],
            $data['province'],
            $data['region'],
            $opened_date
        );
        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => "Failed to create member. {$stmt->error}", 'status' => 500];
        }
        //$member_id = $stmt->insert_id;
        $conn->commit();
        return ['success' => true, 'message' => 'Member created successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error creating member: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_members(int $page = 1, int $per_page = 10): array
{
    try {
        $conn = open_connection();

        $offset = ($page - 1) * $per_page;

        $count_sql = "SELECT COUNT(*) as total FROM (" . vw_member_details() . ") as member_ngani";
        $count_result = $conn->query($count_sql);
        $total_records = $count_result->fetch_assoc()['total'];
        # ORDER BY member_id DESC
        $sql = vw_member_details() . " LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $per_page, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $members = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $total_pages = ceil($total_records / $per_page);
        $has_next = $page < $total_pages;
        $has_prev = $page > 1;

        return [
            'success' => true,
            'message' => 'Members retrieved successfully',
            'data' => [
                'items' => $members,
                'meta_data' => [
                    'current_page' => $page,
                    'per_page' => $per_page,
                    'total_records' => $total_records,
                    'total_pages' => $total_pages,
                    'has_next' => $has_next,
                    'has_prev' => $has_prev
                ]
            ]
        ];
    } catch (Exception $e) {
        log_error("Error fetching members: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_member(int $member_id): array
{
    try {
        $conn = open_connection();

        $sql = vw_member_details() . " WHERE m.member_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => "Member with id {$member_id} is not found.", 'status' => 404];
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $result->fetch_assoc()];
    } catch (Exception $e) {
        log_error("Error fetching member: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_members_by_criteria(array $membership_type, array $membership_status): array
{
    try {
        $conn = open_connection();

        $mt_placeholders = str_repeat('?,', count($membership_type) - 1) . '?';
        $ms_placeholders = str_repeat('?,', count($membership_status) - 1) . '?';

        $sql = vw_member_details() . " WHERE mt.type_name IN ({$mt_placeholders}) AND m.membership_status IN ({$ms_placeholders})";
        $stmt = $conn->prepare($sql);
        $params = array_merge($membership_type, $membership_status);
        $types = str_repeat('s', count($membership_type)) . str_repeat('s', count($membership_status));
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $members = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $members];
    } catch (Exception $e) {
        log_error("Error fetching member: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_member_by_account(int $account_id): array
{
    //log_request('get_member_by_account', ['account_id' => $account_id]);
    try {
        $conn = open_connection();

        $sql = vw_member_details() . " WHERE a.account_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => "Member with the account id {$account_id} is not found.", 'status' => 404];
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $result->fetch_assoc()];
    } catch (Exception $e) {
        log_error("Error fetching member account: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function update_member(int $member_id, array $data): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $sql = "UPDATE members SET `type_id` = ?, first_name = ?, middle_name = ?, last_name = ?, contact_number = ?, house_address = ?, barangay = ?, municipality = ?, province = ?, region = ?";
        $params = [
            $data['type_id'],
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['contact_number'],
            $data['house_address'],
            $data['barangay'],
            $data['municipality'],
            $data['province'],
            $data['region']
        ];
        $types = "isssssssss";

        // Check if membership_status is set and not empty
        if (!empty($data['membership_status'])) {
            $sql .= ", membership_status = ?";
            $params[] = $data['membership_status'];
            $types .= "s";
        }

        $sql .= " WHERE `member_id` = ? LIMIT 1";
        $params[] = $member_id;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $updated = $stmt->execute();

        if (!$updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update member', 'status' => 500];
        }

        $conn->commit();

        $updated_member = get_member($member_id);
        return ['success' => true, 'message' => 'Account updated successfully', 'data' => $updated_member['data']];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating account: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function update_membership_status(int $member_id, string $new_status): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $ms_sql = "UPDATE members SET membership_status = ? WHERE member_id = ? LIMIT 1";
        $ms_stmt = $conn->prepare($ms_sql);
        $ms_stmt->bind_param('si', $new_status, $member_id);
        $ms_updated = $ms_stmt->execute();
        if (!$ms_updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update membership status'];
        }

        if ($new_status === CLOSED) {
            $closed_date = date('Y-m-d');
            $cd_sql = "UPDATE members SET closed_date = ? WHERE member_id = ? LIMIT 1";
            $cd_stmt = $conn->prepare($cd_sql);
            $cd_stmt->bind_param('si', $closed_date, $member_id);
            $cd_updated = $cd_stmt->execute();
            if (!$cd_updated) {
                $conn->rollback();
                return ['success' => false, 'message' => 'Failed to update closed date'];
            }
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => 'Membership status updated successfully.'
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating status: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error updating status: {$e->getMessage()}"];
    }
}

function delete_member(int $member_id): array
{
    try {
        $conn = open_connection();

        $conn->begin_transaction();

        $sql = "DELETE FROM members WHERE member_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $deleted = $stmt->execute();

        if (!$deleted) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to delete member', 'status' => 500];
        }

        $conn->commit();
        return ['success' => true, 'message' => 'Member deleted successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error deleting member: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

/**
 * Balance Management Functions
 */

function update_balance(array $data): array
{
    //int $member_id, float $amount, string $transaction_type
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $member_id = (int)$data['member_id'];
        $caid = (int)$data['caid'];
        $amount = (float)$data['amount'];
        $transaction_type = $data['transaction_type'];

        $member = get_member($member_id);
        if (!$member['success']) {
            return $member;
        }

        $current_balance = $member['data']['current_balance'];
        $new_balance = $current_balance;

        //calculate new balance based on transaction type
        switch ($transaction_type) {
            case DEPOSIT:
            case INTEREST:
                $new_balance += $amount;
                break;
            case WITHDRAWAL:
            case FEE:
                $new_balance -= $amount;
                break;
            default:
                return ['success' => false, 'message' => 'Invalid transaction type', 'status' => 400];
        }

        $sql = "UPDATE members SET current_balance = ? WHERE member_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('di', $new_balance, $member_id);
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Failed to update balance'];
        }

        $transaction_data = [
            'transaction_type' => $transaction_type,
            'amount' => $amount,
            'previous_balance' => $current_balance,
            'new_balance' => $new_balance,
            'notes' => match ($transaction_type) {
                DEPOSIT => 'Regular deposit transaction',
                WITHDRAWAL => 'Regular withdrawal transaction',
                INTEREST => 'Monthly interest credit',
                FEE => 'Account maintenance/penalty fee',
                default => ''
            },
            'created_by' => $_SESSION['account_id'] ?? DEFAULT_ADMIN_ID
        ];

        $recorded = record_transaction($caid, $transaction_data);
        if (!$recorded['success']) {
            $conn->rollback();
            return $recorded;
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => 'Balance updated successfully',
            'data' => [
                'previous_balance' => $current_balance,
                'new_balance' => round($new_balance, 2),
                'transaction_type' => $transaction_type,
                'amount' => round($amount, 2),
                'reference_number' => $recorded['data']['reference_number']
            ]
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating balance: {$e->getMessage()}");
        return ['success' => false, 'message' => "Error updating balance: {$e->getMessage()}"];
    }
}

function apply_account_fees(int $member_id): array
{
    try {
        $member = get_member($member_id);
        if (!$member['success']) {
            return $member;
        }

        $check = check_minimum_balance($member_id);
        if (!$check['success']) {
            return $check;
        }

        if ($check['data']['is_below_minimum']) {
            $penalty_fee = calculate_penalty_fee(
                $member['data']['membership_type'],
                (float)$member['data']['current_balance'],
                (float)$member['data']['minimum_balance'],
                $check['data']['shortage']
            );

            return update_balance($member_id, $penalty_fee, FEE);
        }

        return ['success' => true, 'message' => 'No fees applied'];
    } catch (Exception $e) {
        log_error("Error applying fees: {$e->getMessage()}");
        return ['success' => false, 'message' => "Error applying fees: {$e->getMessage()}"];
    }
}

function check_minimum_balance(int $member_id): array
{
    /**
     * Checks if member's balance is below minimum required for their member(account) type
     */
    try {
        $member = get_member($member_id);
        if (!$member['success']) {
            return $member;
        }

        $current_balance = $member['data']['current_balance'];
        $minimum_balance = $member['data']['minimum_balance'];
        $is_below = $current_balance < $minimum_balance;

        return [
            'success' => true,
            'data' => [
                'is_below_minimum' => $is_below,
                'current_balance' => $current_balance,
                'minimum_required' => $minimum_balance,
                'shortage' => $is_below ? $minimum_balance - $current_balance : 0
            ]
        ];
    } catch (Exception $e) {
        log_error("Error checking minimum balance: {$e->getMessage()}");
        return ['success' => false, 'message' => "Error checking minimum balance: {$e->getMessage()}"];
    }
}

function calculate_penalty_fee(string $account_type, float $current_balance, float $minimum_balance, float $shortage): float
{
    try {
        $conn = open_connection();

        $sql = "SELECT penalty_rate, minimum_penalty_fee, maximum_penalty_fee 
                FROM member_types 
                WHERE type_name = ? LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $account_type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Account type not found');
        }

        $rate_info = $result->fetch_assoc();
        $penalty = $shortage * $rate_info['penalty_rate'];

        // Ensure penalty is within bounds
        return max(
            $rate_info['minimum_penalty_fee'],
            min($penalty, $rate_info['maximum_penalty_fee'])
        );
    } catch (Exception $e) {
        log_error("Error calculating penalty fee: {$e->getTraceAsString()}");
        return MINIMUM_PENALTY;
    }
}

function credit_interest(int $member_id): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        // Get member details
        $member = get_member($member_id);
        if (!$member['success']) {
            return $member;
        }

        $current_balance = $member['data']['current_balance'];
        $interest_rate = $member['data']['interest_rate'];

        // Don't calculate interest for loan accounts
        if (strtolower($member['data']['membership_type']) === 'loan') {
            return [
                'success' => false,
                'message' => 'Interest crediting is not applicable for loan accounts. Use loan amortization for loan interest calculations.',
                'status' => 400
            ];
        }

        // Calculate interest amount for savings accounts
        $interest_amount = $current_balance * ($interest_rate / 100);

        $transaction = update_balance($member_id, $interest_amount, INTEREST);
        return $transaction;
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error crediting interest: {$e->getMessage()}");
        return ['success' => false, 'message' => "Error crediting interest: {$e->getMessage()}"];
    }
}

function get_transactions_by_cooperative(int $member_id, string $cooperative_type): array
{
    try {
        $conn = open_connection();

        $base_sql = vw_member_transaction_history();
        $sql = $base_sql . " WHERE m.member_id = ? AND t.type_name = ? ORDER BY mt.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $member_id, $cooperative_type);
        $stmt->execute();
        $result = $stmt->get_result();

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }

        return [
            'success' => true,
            'message' => 'Member transactions retrieved successfully',
            'data' => $transactions
        ];
    } catch (Exception $e) {
        log_error("Error fetching member transactions: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error: {$e->getMessage()}"];
    }
}

function get_member_transactions(int $member_id): array
{
    try {
        $conn = open_connection();

        $base_sql = vw_member_transaction_history();
        $sql = $base_sql . " WHERE m.member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }

        return [
            'success' => true,
            'message' => 'Member transactions retrieved successfully',
            'data' => $transactions
        ];
    } catch (Exception $e) {
        log_error("Error fetching member transactions: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error: {$e->getMessage()}"];
    }
}

function get_members_transactions(): array
{
    try {
        $conn = open_connection();

        $sql = vw_member_transaction_history();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $transactions = $result->fetch_all(MYSQLI_ASSOC);
        return [
            'success' => true,
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions
        ];
    } catch (Exception $e) {
        log_error("Error fetching members transactions: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error: {$e->getMessage()}"];
    }
}

function record_transaction(int $cooperative_id, array $data): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $reference_number = 'TXN' . date('Ymd') . generate_random_number(4);

        $sql = "INSERT INTO member_transactions (
            cooperative_id, transaction_type, amount, previous_balance,
            new_balance, reference_number, notes, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'isdddssi',
            $cooperative_id,
            $data['transaction_type'],
            $data['amount'],
            $data['previous_balance'],
            $data['new_balance'],
            $reference_number,
            $data['notes'],
            $data['created_by']
        );
        $created = $stmt->execute();

        if (!$created) {
           $conn->rollback();
            return ['success' => false, 'message' => 'Failed to record transaction'];
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => 'Transaction recorded successfully',
            'data' => ['reference_number' => $reference_number]
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error recording transaction: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error: {$e->getMessage()}"];
    }
}

function update_member_credit_balance(int $member_id, float $new_credit_balance): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $sql = "UPDATE members SET credit_balance =? WHERE member_id =? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('di', $new_credit_balance, $member_id);
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false,'message' => 'Failed to update member credit balance'];
        }
        $conn->commit();
        return ['success' => true,'message' => 'Member balance updated successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating member credit balance: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}


function update_member_current_balance(int $member_id, float $new_balance): array {
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $sql = "UPDATE members SET current_balance =? WHERE member_id =? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('di', $new_balance, $member_id);
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false,'message' => 'Failed to update member current balance'];
        }

        $conn->commit();
        return ['success' => true,'message' => 'Member current balance updated successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating member current balance: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}


// MEMBER METRICS

function get_member_account_balance_metrics(int $member_id): array {
    try {
        $conn = open_connection();

        $sql = "SELECT
            m.current_balance AS total_current_balance,
            m.credit_balance,
            (SELECT COALESCE(SUM(amount), 0) 
            FROM member_transactions 
            WHERE member_id = m.member_id 
            AND transaction_type = 'withdrawal' 
            AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)) AS total_withdrawals_last_30d
        FROM members m
        WHERE m.member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false,'message' => 'Member not found'];
        }

        $metrics = $result->fetch_assoc();
        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $metrics];
    } catch (Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_member_savings_goal_metrics(int $member_id): array {
    try {
        $conn = open_connection();

        $sql = "SELECT 
            mt.type_name AS member_type_name,
            m.current_balance AS savings_current_balance,
            mt.minimum_balance AS savings_target_balance,
            CASE 
                WHEN mt.minimum_balance > 0 THEN (m.current_balance / mt.minimum_balance) * 100
                ELSE 0 
            END AS savings_progress_percentage
        FROM cooperative_accounts ca
        INNER JOIN members m ON ca.member_id = m.member_id
        INNER JOIN member_types mt ON ca.type_id = mt.type_id
        WHERE mt.type_name IN ('Savings Account', 'Time Deposit', 'Fixed Deposit', 'Special Savings', 'Youth Savings')
            AND m.member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // if ($result->num_rows === 0) {
        //     return ['success' => false,'message' => 'Member not found'];
        // }

        $metrics = $result->num_rows > 0 ? $result->fetch_assoc() : [];
        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $metrics];
    } catch (Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_member_active_loans_metrics(int $member_id): array {
    try {
        $conn = open_connection();

        $sql = "SELECT 
            COUNT(ma.amortization_id) AS total_active_loans_count,
            COALESCE(SUM(ma.principal_amount), 0) AS total_loan_principal,
            COALESCE(SUM(CASE WHEN ma.status = 'overdue' THEN ma.remaining_balance ELSE 0 END), 0) AS total_overdue_amount,
            COALESCE(SUM(CASE WHEN ma.status = 'overdue' THEN 1 ELSE 0 END), 0) AS overdue_loans_count
        FROM 
            member_amortizations ma
        WHERE ma.status IN ('pending', 'overdue') 
            AND ma.approval = 'approved'
            AND ma.member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false,'message' => 'Member not found'];
        }

        $metrics = $result->fetch_assoc();
        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $metrics];
    } catch (Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_member_account_status_metrics(int $member_id): array {
    try {
        $conn = open_connection();

        $sql = "SELECT 
            m.membership_status,
            m.opened_date AS member_since_date,
            CASE 
                WHEN EXISTS (SELECT 1 FROM member_amortizations WHERE member_id = m.member_id AND status = 'overdue' AND approval = 'approved') THEN 'Overdue Payments'
                WHEN EXISTS (SELECT 1 FROM member_amortizations WHERE member_id = m.member_id AND status = 'pending' AND approval = 'approved') THEN 'Payments Pending'
                WHEN EXISTS (SELECT 1 FROM member_amortizations WHERE member_id = m.member_id AND approval = 'approved') THEN 'On Time'
                ELSE 'No Active Loans'
            END AS loan_payment_status
        FROM 
            members m
        WHERE m.member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false,'message' => 'Member not found'];
        }

        $metrics = $result->fetch_assoc();
        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $metrics];
    } catch (Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_member_upcoming_payments(int $member_id): array {
    try {
        $conn = open_connection();

        $sql = "SELECT 
            ma.amortization_id,
            at.type_name AS loan_type,
            ma.monthly_amount,
            DATE_ADD(ma.start_date, INTERVAL (SELECT COUNT(*) FROM amortization_payments WHERE amortization_id = ma.amortization_id) MONTH) AS next_due_date_estimate
        FROM 
            member_amortizations ma
        JOIN
            amortization_types at ON ma.type_id = at.type_id
        WHERE ma.status IN ('pending', 'overdue')
            AND ma.approval = 'approved'
            AND ma.member_id = ?
        ORDER BY
            next_due_date_estimate ASC
        LIMIT 5";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $metrics = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $metrics];
    } catch (Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_cooperative_accounts_by_criteria(array $membership_type, array $status): array {
    try {
     $conn = open_connection();

        $mt_placeholders = str_repeat('?,', count($membership_type) - 1) . '?';
        $status_placeholders = str_repeat('?,', count($status) - 1). '?';

        $sql = vw_cooperative_accounts_details() . " WHERE mt.type_name IN ({$mt_placeholders}) AND ca.status IN ({$status_placeholders})";
        $stmt = $conn->prepare($sql);
        $params = array_merge($membership_type, $status);
        $types = str_repeat('s', count($membership_type)) . str_repeat('s', count($status));
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $members = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $members];   
    } catch (Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_members_by_approval(array $approval_status): array {
    try {
        $conn = open_connection();
        $as_placeholders = str_repeat('?,', count($approval_status) - 1). '?';

        $sql = vw_member_details() . " WHERE m.approval_status IN ({$as_placeholders})";
        $stmt = $conn->prepare($sql);
        $params = array_merge($approval_status);
        $types = str_repeat('s', count($approval_status));
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $members = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $members];
    } catch (Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}


function register_member(array $data): array {
    try {
        log_request('received in services: ', $data);

        $conn = open_connection();
        $conn->begin_transaction();
        $conn->autocommit(false);

        $account_uid = 'A' . generate_random_number(6);
        $hashed_password = hash_password($data['confirm_password']);
        $active = 'active';

        $profile_img = null;
        if (isset($data['profile_picture']) && is_array($data['profile_picture']) && !empty($data['profile_picture']['name'])) {
            $profile_img = $data['profile_picture']['name'];
        } elseif (isset($data['profile_picture']) && is_string($data['profile_picture'])) {
            $profile_img = $data['profile_picture'];
        }

        //insert account table
        $a_sql = "INSERT INTO accounts (role_id, account_uid, email, email_verified_at, username, `password`, profile_image, account_status)
            VALUES (?,?,?,NOW(),?,?,?,?)
        ";
        $a_stmt = $conn->prepare($a_sql);
        $a_stmt->bind_param('issssss', $data['role_id'], $account_uid, $data['email'], $data['username'], $hashed_password, $profile_img, $active);
        $a_created = $a_stmt->execute();
        if (!$a_created) {
            $conn->rollback();
            return ['success' => false,'message' => 'Failed to create account'];
        }
        $account_id = $a_stmt->insert_id;
        //return ['success' => true,'message' => 'Account created successfully: '.$account_id];

        $member_uid = 'M' . generate_random_number(6);
        $approval_status = $data['page_from'] === 'internal'? 'approved' : 'pending';

        //insert member table
        $m_sql = "INSERT INTO members (account_id, member_uid, approval_status, first_name, middle_name, last_name, sex, contact_number, house_address, barangay, municipality, province, region)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
        ";
        $m_stmt = $conn->prepare($m_sql);
        $m_stmt->bind_param('issssssssssss', $account_id, $member_uid, $approval_status, $data['first_name'], $data['middle_name'], $data['last_name'], $data['sex'], $data['contact_number'], $data['house_address'], $data['barangay'], $data['municipality'], $data['province'], $data['region']);
        $m_created = $m_stmt->execute();
        if (!$m_created) {
            $conn->rollback();
            return ['success' => false,'message' => 'Failed to create member'];
        }
        $member_id = $m_stmt->insert_id;
        //return ['success' => true,'message' => 'Member created successfully: '.$member_id];

        $opened_date = date('Y-m-d');
        $ca_status = $data['page_from'] === 'internal'? 'active' : 'pending';

        # $data['selected_caids'] = '6,7'
        $type_id_array = array_map('trim', explode(',', $data['selected_caids']));
        foreach ($type_id_array as $type_id) {
            $ca_sql = "INSERT INTO cooperative_accounts (type_id, member_id, opened_date, status)
                VALUES (?,?,?,?)
            ";
            $ca_stmt = $conn->prepare($ca_sql);
            $ca_stmt->bind_param('iiss', $type_id, $member_id, $opened_date, $ca_status);
            $ca_created = $ca_stmt->execute();
            if (!$ca_created) {
                $conn->rollback();
                return ['success' => false,'message' => 'Failed to create cooperative account'];
            }
        }

        $conn->commit();
        return ['success' => true,'message' => 'Member registered successfully'];
    } catch(Exception $e) {
        $conn->rollback();
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function apply_services_to_member(int $member_id, array $services): array {
    try {
        $conn = open_connection();
        $conn->begin_transaction();
        $conn->autocommit(false);

        //$member_id = $data['member_id'];
        $opened_date = date('Y-m-d');
        $ca_status = 'active';
        foreach ($services as $type_id) {
            $ca_sql = "INSERT INTO cooperative_accounts (type_id, member_id, opened_date, status)
                VALUES (?,?,?,?)
            ";
            $ca_stmt = $conn->prepare($ca_sql);
            $ca_stmt->bind_param('iiss', $type_id, $member_id, $opened_date, $ca_status);
            $ca_created = $ca_stmt->execute();
            if (!$ca_created) {
                $conn->rollback();
                return ['success' => false,'message' => 'Failed to create cooperative account'];
            }
        }

        $conn->commit();
        return ['success' => true,'message' => 'Services applied successfully'];
    } catch(Exception $e) {
        $conn->rollback();
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function update_registered_member(array $data): array {
    try {
        log_request('received in services: ', $data);

        $conn = open_connection();
        $conn->begin_transaction();
        $conn->autocommit(false);

        //update account table
        $base_sql = "UPDATE accounts SET email=?, username=?";
        $params = [$data['email'], $data['username']];
        $types = 'ss';

        if(!empty($data['profile_picture'])) {
            $base_sql .= ", profile_image=?";
            $params[] = $data['profile_picture'];
            $types .= 's';
        }

        if(isset($data['password']) && $data['password'] !== 'null' && $data['password'] !== null && !empty($data['confirm_password'])) {
            $hashed_password = hash_password($data['confirm_password']);
            $base_sql.= ", password=?";
            $params[] = $hashed_password;
            $types.='s';
        }

        $base_sql.=" WHERE account_id=?";
        $params[] = $data['account_id'];
        $types.='i';

        $a_sql = $base_sql;
        $a_stmt = $conn->prepare($a_sql);
        $a_stmt->bind_param($types,...$params);
        $a_updated = $a_stmt->execute();
        if (!$a_updated) {
            $conn->rollback();
            return ['success' => false,'message' => 'Failed to update account'];
        }

        //update member table
        $base_sql = "UPDATE members SET first_name=?, last_name=?, sex=?, contact_number=?, house_address=?, barangay=?, municipality=?, province=?, region=?";
        $params = [$data['first_name'], $data['last_name'], $data['sex'], $data['contact_number'], $data['house_address'], $data['barangay'], $data['municipality'], $data['province'], $data['region']];
        $types ='sssssssss';

        if(!empty($data['middle_name'])) {
            $base_sql.= ", middle_name=?";
            $params[] = $data['middle_name'];
            $types.='s';
        }

        $base_sql.=" WHERE member_id=?";
        $params[] = $data['member_id'];
        $types.='i';

        $m_sql = $base_sql;
        $m_stmt = $conn->prepare($m_sql);
        $m_stmt->bind_param($types,...$params);
        $m_updated = $m_stmt->execute();
        if (!$m_updated) {
            $conn->rollback();
            return ['success' => false,'message' => 'Failed to update member'];
        }

        //update accounts cooperative
        if (!empty($data['account_changes']) && is_array($data['account_changes'])) {
            $ca_stmt = $conn->prepare("UPDATE cooperative_accounts SET `status`=? WHERE caid=?");
            foreach ($data['account_changes'] as $account) {
                // Only update if something changed
                if ($account['status'] !== $account['original_status']) {
                    $ca_stmt->bind_param('si', $account['status'], $account['caid']);
                    if (!$ca_stmt->execute()) {
                        $conn->rollback();
                        return ['success' => false, 'message' => 'Failed to update cooperative account: ' . $ca_stmt->error];
                    }
                }
            }
        }
        
        $conn->commit();
        return ['success' => true, 'message' => 'Info updated successfully.'];
    } catch(Exception $e) {
        $conn->rollback();
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_registered_member(int $member_id): array {
    try {
        $conn = open_connection();

        $sql = vw_registered_member_details() . " WHERE m.member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Member not found'];
        }

        $member = $result->fetch_assoc();
        
        // Decode the cooperative_accounts JSON string into a PHP array
        if (!empty($member['cooperative_accounts'])) {
            $member['cooperative_accounts'] = json_decode($member['cooperative_accounts'], true);
        } else {
            $member['cooperative_accounts'] = []; // Set to empty array if null or empty
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $member];
    } catch(Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Error: {$e->getMessage()}"];
    }
}

function get_registered_members(): array {
    try {
        $conn = open_connection();

        $sql = vw_registered_member_details() . " WHERE m.approval_status = 'approved'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $members =  $result->num_rows > 0? $result->fetch_all(MYSQLI_ASSOC) : [];
        return ['success' => true,'message' => 'Retrieved successfully', 'data' => $members];
    } catch(Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function update_member_approval_status(int $member_id, string $approval_status): array {
    try {
        $conn = open_connection();
        $conn->begin_transaction();
        $conn->autocommit(false);

        $m_sql = "UPDATE members SET approval_status=? WHERE member_id=? LIMIT 1";
        $m_stmt = $conn->prepare($m_sql);
        $m_stmt->bind_param('si', $approval_status, $member_id);
        $updated = $m_stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false,'message' => 'Failed to update member approval status'];
        }

        if($approval_status === 'approved') {
            $ca_sql = "UPDATE cooperative_accounts SET `status`='active' WHERE member_id=?";
            $ca_stmt = $conn->prepare($ca_sql);
            $ca_stmt->bind_param('i', $member_id);
            $updated = $ca_stmt->execute();
            if (!$updated) {
                $conn->rollback();
                return ['success' => false,'message' => 'Failed to update member cooperative account status'];
            }
        }

        $conn->commit();
        return ['success' => true,'message' => 'Approval status updated successfully'];
    } catch(Exception $e) {
        $conn->rollback();
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_cooperative_account_by_type(int $member_id, string $type_name): array {
    try {
        $conn = open_connection();

        $sql = vw_cooperative_accounts_details(). " WHERE m.member_id = ? AND mt.type_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $member_id, $type_name);
        $stmt->execute();
        $result = $stmt->get_result();

        $account =  $result->num_rows > 0? $result->fetch_assoc() : [];
        return ['success' => true,'message' => 'Retrieved successfully', 'data' => $account];
    } catch(Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}

function get_cooperative_account(int $caid): array {
    try {
        $conn = open_connection();

        $sql = vw_cooperative_accounts_details(). " WHERE ca.caid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $caid);
        $stmt->execute();
        $result = $stmt->get_result();

        $account =  $result->num_rows > 0? $result->fetch_assoc() : [];
        return ['success' => true,'message' => 'Retrieved successfully', 'data' => $account];
    } catch(Exception $e) {
        log_error("Error: {$e->getTraceAsString()}");
        return ['success' => false,'message' => "Error: {$e->getMessage()}"];
    }
}