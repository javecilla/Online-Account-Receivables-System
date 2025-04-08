<?php

declare(strict_types=1);

function get_type(int $type_id): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT * FROM member_types WHERE `type_id` = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $type_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => "Member type with id {$type_id} is not found.", 'status' => 404];
        }

        return ['success' => true, 'message' => 'Retrieved successfully', 'data' => $result->fetch_assoc()];
    } catch (Exception $e) {
        log_error("Error fetching member type detail: {$e->getMessage()}");
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function get_membership_types(): array
{
    try {
        $conn = open_connection();

        $sql = "SELECT * FROM member_types";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $types = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

        return [
            'success' => true,
            'message' => 'Membership types retrieved successfully',
            'data' => $types
        ];
    } catch (Exception $e) {
        log_error("Error fetching membership types: {$e->getMessage()}");
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}
