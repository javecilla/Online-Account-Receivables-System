<?php

declare(strict_types=1);

function update_contact_map(array $data): array {
  try {
    $conn = open_connection();
    $conn->autocommit(false);
    $stmt = $conn->prepare("UPDATE contact_map SET latitude = ?, longitude = ?, popup = ? WHERE id = ?");
    $stmt->bind_param("sssi", $data['latitude'], $data['longitude'], $data['popup'], $data['contact_id']);
    $updated = $stmt->execute();
    
    if(!$updated) {
      $conn->rollback();
      return ['success' => false, 'message' => 'Failed to update contact map'];
    }

    $conn->commit();
    return ['success' => true, 'message' => 'Contact map updated successfully'];
  } catch (Exception $e) {
      $conn->rollback();
      log_error("Error: {$e->getTraceAsString()}");
      return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
  }
}

function get_contact_map() {
  try {
    $conn = open_connection();
    $stmt = $conn->prepare("SELECT * FROM contact_map LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();

    $contacts = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    return ['success' => true, 'contacts' => $contacts];
  } catch (Exception $e) {
      log_error("Error: {$e->getTraceAsString()}");
      return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
  }
}

function create_contact_messages(array $data): array {
  try {
    $conn = open_connection();
    $conn->autocommit(false);
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data['name'], $data['email'], $data['message']);
    $created = $stmt->execute();
    
    if(!$created) {
      $conn->rollback();
      return ['success' => false,'message' => 'Failed to send message'];
    }

    $conn->commit();
    return ['success' => true, 'message' => 'Message sent successfully'];
  } catch (Exception $e) {
      $conn->rollback();
      log_error("Error: {$e->getTraceAsString()}");
      return ['success' => false,'message' => "Database error occurred: {$e->getMessage()}"];
  }
}

function get_aboutus_content() {
  try {
    $conn = open_connection();
    $stmt = $conn->prepare("SELECT * FROM aboutus_content LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();

    $aboutus = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    return ['success' => true, 'aboutus' => $aboutus];
  } catch (Exception $e) {
      log_error("Error: {$e->getTraceAsString()}");
      return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
  }
}
