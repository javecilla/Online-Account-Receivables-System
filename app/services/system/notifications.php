<?php

declare(strict_types=1);

function create_notification(array $data): array {
  try {
    $conn = open_connection();

    $sql = "INSERT INTO notifications (account_id, title, `message`, `type`)
      VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $data['account_id'], $data['title'], $data['message'], $data['type']);
    $created = $stmt->execute();
    if(!$created) {
      return ['success' => false, 'message' => 'Error creating notification'];
    }
    $notification_id = $stmt->insert_id;

    return [
      'success' => true,
      'message' => 'Notification created',
      'data' => [
        'notification_id' => $notification_id
      ]
    ];
  } catch(Exception $e) {
    log_error("Error creating notification: {$e->getMessage()}");
    return ['success' => false, 'message' => "Database error: {$e->getMessage()}"];
  }
}

function send_notification_email(string $email): array
{
    try {
        $conn = open_connection();

        $email_body_content = <<<HTML
            <p style="margin: 0; font-size: 12px; font-weight: bold; color: #000;">Good day, Ka-ITech,</p>
            <p style="margin: 5px 0; font-size: 12px; color: #000;">You're receiving this email because we've received a request to verify the account associated with this email address: <strong style="text-decoration: none; color: #000;">{$email}</strong>.</p>
            <p style="margin: 15px 0 10px 0; font-size: 12px; color: #000;">To complete the verification process, you can either:</p>
            
            <div style="margin: 20px 0; text-align: center; font-size: 20px; font-weight: bold; color: #000; padding: 5px; border-radius: 8px;">
                <a href="{$verification_url}" target="_blank" style="background-color: #474747; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-family: Arial, sans-serif; font-size: 16px;">Verify My Account</a>
            </div>

            <p style="text-align: center; font-size: 12px; color: #666; margin: 10px 0;">OR</p>
        
            <div style="margin: 10px 0; padding: 10px; background-color: #f5f5f5; border-radius: 5px;">
                <p style="margin: 0 0 5px 0; font-size: 12px; color: #666;">Copy and paste this link in your browser:</p>
                <p style="margin: 0; font-size: 11px; color: #474747; word-break: break-all;">{$verification_url}</p>
            </div>

            <p style="margin: 15px 0 5px 0; font-size: 12px; color: #000;">Your verification link is valid for the next <strong>10 minutes</strong>.</p>
            <p style="margin: 5px 0; font-size: 12px; color: #000;">If you did not initiate this request, you can safely ignore this email.</p>
        HTML;

        $mailed = send_email($email, 'Verify Your Account - Action Required', $email_body_content);
        if (!$mailed['success']) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Error sending verification email: ' . $mailed['message'], 'status' => 500];
        }

        $conn->commit();
        return [
            'success' => true,
            'message' => "An account verification has been sent to your email {$email}. Please check your inbox, spams, or junk mail and use the code within 10 minutes.",
        ];
    } catch (Exception $e) {
        $conn->rollback();
        log_error("Error sending verification email: {$e->getTraceAsString()}");
        return ['success' => false, 'message' => "Database error occurred: {$e->getMessage()}"];
    }
}