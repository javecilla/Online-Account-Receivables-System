<?php

declare(strict_types=1);

function get_employee(int $employee_id): array
{
    try {
        $conn = open_connection();

        $sql = vw_employee_details() . " WHERE employee_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $employee_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => "Employee with id {$employee_id} is not found.", 'status' => 404];
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $result->fetch_assoc()];
    } catch (Exception $e) {
        log_error("Error fetching employee: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_employee_by_account(int $account_id): array
{
    try {
        $conn = open_connection();

        $sql = vw_employee_details() . " WHERE account_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => "Employee with the account id {$account_id} is not found.", 'status' => 404];
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $result->fetch_assoc()];
    } catch (Exception $e) {
        log_error("Error fetching employee account: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}
