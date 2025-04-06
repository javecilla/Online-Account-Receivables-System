<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../config/logger.php';
require_once __DIR__ . '/../../helpers/system.php';
require_once __DIR__ . '/../../helpers/global.php';
require_once __DIR__ . '/../../../assets/libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../../assets/libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../../assets/libs/PHPMailer/src/SMTP.php';

function send_email(string $to, string $subject, string $body, string $altBody = '', array $attachments = []): array
{
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $body_template = <<<HTML
        <!DOCTYPE html>
        <html>
        <head></head>
            <meta charset="UTF-8">
        </head>
        <body>
            <div style="font-family: Arial, sans-serif; background-color: #f8f8f8; padding: 0px; line-height: 1.2; color: #000;">
                <div style="width: 100%; margin: 0; background-color: #ffffff; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                    <!-- Header Section -->
                    <div style="text-align: center; padding: 0;">
                        <img src="https://jerome-avecilla.infinityfreeapp.com/assets/images/email-banner.png" alt="Header Banner" style="width: 100%; height: auto;" />
                    </div>
                    <!-- Body Section -->
                    <div style="padding: 15px; color: #000;"> 
                        $body
                    </div>
                    <!-- Footer Section -->
                    <div style="text-align: center; background-color: #f3f3f3; padding: 20px 0 20px 0; font-size: 10px; color: #000;">
                        <p style="margin: 0; color: #000;">Created by: <a href="https://jerome-avecilla.vercel.app/" target="_blank">Jerome Avecilla</a></p>
                        <p style="margin: 0; color: #000;">Section: BSIT 2F-G2</p>
                        <p style="margin: 0; color: #000;">Subject: IT211 - Web System Technologies</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        HTML;

        // Debug level based on environment
        $debug = env('APP_ENV') === 'production' ? 0 : 2;
        $mail->SMTPDebug = $debug;
        $mail->Debugoutput = function ($str, $level) {
            log_error("PHPMailer Debug: $str");
        };

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = env('MAIL_ENCRYPTION');
        $mail->Port = (int)env('MAIL_PORT');

        // Sender and Recipient
        $mail->From = env('MAIL_FROM_SENDER');
        $mail->FromName = "<no-reply>" . env('MAIL_FROM_NAME');
        $mail->addAddress($to);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body_template;
        $mail->AltBody = $altBody;

        // Send attachment
        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                $mail->addAttachment($attachment);
            } else {
                return [
                    'success' => false,
                    'message' => "Attachment file not found: $attachment",
                    'status' => 400
                ];
            }
        }

        if (!$mail->send()) {
            log_error('Mail Error', [
                'error' => $mail->ErrorInfo,
                'to' => $to,
                'subject' => $subject
            ]);
            return [
                'success' => false,
                'message' => 'Failed to send verification email: ' . $mail->ErrorInfo,
                'status' => 500
            ];
        }

        return [
            'success' => true,
            'message' => 'Verification email sent successfully.',
            'status' => 200
        ];
    } catch (\Exception $e) {
        log_error('Mail Exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return [
            'success' => false,
            'message' => 'Error sending verification email: ' . $e->getMessage(),
            'status' => 500
        ];
    }
}
