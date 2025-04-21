<?php

declare(strict_types=1);

function handle_create_notification(mixed $payload): void
{
  $validated = validate_data($payload, [
    'account_id' =>'required|numeric|min:1|check:account_model',
    'title' =>'required',
    'message' =>'required',
    'type' => 'required|in:payment_reminder,overdue_notice,system_alert',
    'member_id' =>'optional|numeric|min:1|check:member_model',
    'employee_id' =>'optional|numeric|min:1|check:employee_model',
    'name' =>'required',
    'email' =>'required|email',
    'amortization_id' => 'optional|numeric|min:1|check:amortization_model',
  ]);

  $created = create_notification($validated['data']);
  if(!$created) {
    return_response($created);
  }

  $recipient_name = htmlspecialchars($validated['data']['name']);
  $recipient_email = $validated['data']['email']; #$validated['data']['email'] | jeromesavc@gmail.com
  $notification_type = $validated['data']['type'];
  $notification_title = htmlspecialchars($validated['data']['title']);
  $notification_message = nl2br(htmlspecialchars($validated['data']['message']));

  $email_subject = "OARSMC Avecilla - {$notification_title}";
  $email_body_content = '';

  $email_body_content .= <<<HTML
    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
      <p>Dear {$recipient_name},</p>
  HTML;

  $portal_link = env('APP_URL') . '/auth/login';

  switch ($notification_type) {
    case 'payment_reminder':
      $email_body_content .= <<<HTML
        <p>This is a friendly reminder regarding your account.</p>
        <p><strong>{$notification_title}</strong></p>
        <p>{$notification_message}</p>
        <p>Please log in to your account portal to view details and make payments if necessary.</p>
        <p><a href="{$portal_link}" style="color: #007bff; text-decoration: none;">Access Account Portal</a></p>
      HTML;
      break;

    case 'overdue_notice':
      $email_body_content .= <<<HTML
        <p>This email is regarding an overdue item on your account.</p>
        <p><strong>{$notification_title}</strong></p>
        <p>{$notification_message}</p>
        <p>Prompt attention to this matter is appreciated. Please log in to your account portal to resolve the outstanding balance.</p>
        <p><a href="{$portal_link}" style="color: #007bff; text-decoration: none;">Access Account Portal</a></p>
      HTML;
      break;

    case 'system_alert':
    default:
      $email_body_content .= <<<HTML
        <p>Please be advised of the following system notification:</p>
        <p><strong>{$notification_title}</strong></p>
        <p>{$notification_message}</p>
        <p>If any action is required, please log in to your account portal.</p>
        <p><a href="{$portal_link}" style="color: #007bff; text-decoration: none;">Access Account Portal</a></p>
      HTML;
      break;
  }

  $email_body_content .= <<<HTML
      <p>If you believe you received this email in error, please disregard it or contact us immediately.</p>
    </div>
  HTML;

  $mailed = send_email($recipient_email, $email_subject, $email_body_content);
  if (!$mailed['success']) {
      log_error("Failed to send notification email to {$recipient_email}. Type: {$notification_type}. Error: " . $mailed['message']);
      return_response(['success' => false, 'message' => 'Notification saved, but failed to send email: ' . $mailed['message'], 'status' => 500]);
  }
  
  // log_request("create_notification_processed", $validated['data']);
  return_response(['success' => true, 'message' => 'Notification created successfully.']);
}