<?php

declare(strict_types=1);

function handle_update_contact_map(mixed $payload): void {
    $validated = validate_data($payload, [
        'contact_id' => 'required',
        'latitude' => 'required',
        'longitude' => 'required',
        'popup' => 'required'
    ]);

    $updated = update_contact_map($validated['data']);
    return_response($updated);
}

function handle_get_contact_map(mixed $payload): void
{
    // $validated = validate_data($payload, [
    //     'contact_id' => 'required',
    //     'latitude' => 'required',
    //     'longitude' => 'required',
    //     'popup' => 'required'
    // ]);

    // log_request('payload validated', $validated['data']);
    // return_response(['success' => true, 'message' => 'test']);
    $contact = get_contact_map();
    return_response($contact);
}

function handle_create_contact_messages(mixed $payload): void
{
  $validated = validate_data($payload, [
      'name' =>'required',
      'email' =>'required|email',
      'message' =>'required',
      'recaptcha_response' => 'required'
  ]);

  $verified = verify_recaptcha($validated['data']['recaptcha_response'], get_client_ip());
  if(!$verified['success']) {
      return_response($verified);
  }

  $created = create_contact_messages($validated['data']);
  // log_request('payload validated', $validated['data']);
  // return_response(['success' => true,'message' => 'test']);
  $from = $validated['data']['email'];
  $to = 'jeromesavc@gmail.com';
  $subject = 'Contact Form Submission';
  $message = nl2br(htmlspecialchars($validated['data']['message']));

  if(!$created['success']){
      return_response($created);
  }

  $email_body_content = <<<HTML
    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
        <h2 style="color: #0056b3;">$subject</h2>
        <p><strong>Sender:</strong> $from</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        $message
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p>Thank you,<br>OARSMC Avecilla</p>
        <p style="font-size: 0.8em; color: #777;">
            <strong>Note:</strong> This is an auto-generated email. Please do not reply to this message.
        </style>
    </div>
  HTML;

  $mailed = send_email($to, $subject, $email_body_content);
  if (!$mailed['success']) {
      return_response(['success' => false, 'message' => 'Failed to send email: ' . $mailed['message'], 'status' => 500]);
  }

  return_response(['success' => true,'message' => 'Contact message created successfully','status' => 201]);
}


function handle_get_aboutus_content(mixed $payload): void
{
    // $validated = validate_data($payload, [
    //     'aboutus_id' => 'required',
    //     'title' => 'required',
    //     'description' => 'required',
    //     'features' => 'required'
    // ]);

    // log_request('payload validated', $validated['data']);
    // return_response(['success' => true, 'message' => 'test']);
    $about = get_aboutus_content();
    return_response($about);
}