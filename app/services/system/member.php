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

        $sql = vw_member_details() . " WHERE member_id = ? LIMIT 1";
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

function update_balance(int $member_id, float $amount, string $transaction_type): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

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

        $recorded = record_transaction($member_id, $transaction_data);
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

function get_member_transactions(int $member_id): array
{
    try {
        $conn = open_connection();

        $base_sql = vw_member_transaction_history();
        $sql = $base_sql . "WHERE m.member_id = ?";
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

function record_transaction(int $member_id, array $data): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $reference_number = 'TXN' . date('Ymd') . generate_random_number(4);

        $sql = "INSERT INTO member_transactions (
            member_id, transaction_type, amount, previous_balance,
            new_balance, reference_number, notes, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'isdddssi',
            $member_id,
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