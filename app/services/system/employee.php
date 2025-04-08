<?php

declare(strict_types=1);

function create_employee(array $data): array
{
    try {
        $conn = open_connection();

        $conn->begin_transaction();

        $sql = "INSERT INTO employees (
            account_id,
            first_name,
            middle_name,
            last_name,
            contact_number,
            salary,
            rata
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'issssdd',
            $data['account_id'],
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['contact_number'],
            $data['salary'],
            $data['rata']
        );
        $created = $stmt->execute();
        if (!$created) {
            $conn->rollback();
            return ['success' => false, 'message' => "Failed to create employee. {$stmt->error}", 'status' => 500];
        }
        //$employee_id = $stmt->insert_id;
        $conn->commit();
        return ['success' => true, 'message' => 'Employee created successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error creating enmployee: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}

function get_employee(int $employee_id): array
{
    try {
        $conn = open_connection();

        $sql = vw_employee_details() . " WHERE e.employee_id = ? LIMIT 1";
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

        $sql = vw_employee_details() . " WHERE a.account_id = ? LIMIT 1";
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

function update_employee(int $employee_id, array $data): array
{
    try {
        $conn = open_connection();
        $conn->begin_transaction();

        $sql = "UPDATE employees SET first_name = ?, middle_name = ?, last_name = ?, contact_number = ?, salary = ?, rata = ?
            WHERE employee_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'ssssddi',
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['contact_number'],
            $data['salary'],
            $data['rata'],
            $employee_id
        );
        $updated = $stmt->execute();
        if (!$updated) {
            $conn->rollback();
            return ['success' => false, 'message' => "Failed to update employee. {$stmt->error}", 'status' => 500];
        }

        $conn->commit();
        return ['success' => true, 'message' => 'Employee updated successfully'];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error updating employee: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}
