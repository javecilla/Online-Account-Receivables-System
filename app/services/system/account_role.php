<?php

declare(strict_types=1);

function get_role(int $id): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT * FROM account_roles WHERE role_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => "Account role with id {$id} is not found.", 'status' => 404];
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $result->fetch_assoc()];
    } catch (Exception $e) {
        log_error("Error fetching role detail: {$e->getMessage()}");
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function get_account_roles(): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT * FROM account_roles";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $roles = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return [
            'success' => true,
            'message' => 'Accounts roles retrieved successfully',
            'data' => $roles
        ];
    } catch (Exception $e) {
        log_error("Error fetching account roles: {$e->getMessage()}");
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}
