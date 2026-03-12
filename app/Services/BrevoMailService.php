<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoMailService
{
    private const API_URL = 'https://api.brevo.com/v3/smtp/email';

    public function sendEmail(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlContent,
        ?string $textContent = null,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): array {
        $apiKey = config('services.brevo.key');
        $senderEmail = config('services.brevo.sender_email');
        $senderName = config('services.brevo.sender_name', config('app.name', 'NexRig'));

        if (empty($apiKey)) {
            throw new \RuntimeException('BREVO_API_KEY is not configured.');
        }

        if (empty($senderEmail)) {
            throw new \RuntimeException('BREVO_SENDER_EMAIL is not configured.');
        }

        $payload = [
            'sender' => [
                'name' => $senderName,
                'email' => $senderEmail,
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName,
                ],
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ];

        if (!empty($textContent)) {
            $payload['textContent'] = $textContent;
        }

        $replyToEmail = $replyToEmail ?: $senderEmail;
        $replyToName = $replyToName ?: $senderName;

        $payload['replyTo'] = [
            'email' => $replyToEmail,
            'name' => $replyToName,
        ];

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'api-key' => $apiKey,
            'content-type' => 'application/json',
        ])->timeout(20)->post(self::API_URL, $payload);

        if ($response->successful()) {
            $data = $response->json() ?? [];

            Log::info('Brevo email sent successfully', [
                'to' => $toEmail,
                'subject' => $subject,
                'message_id' => $data['messageId'] ?? null,
            ]);

            return [
                'success' => true,
                'message_id' => $data['messageId'] ?? null,
                'response' => $data,
            ];
        }

        Log::error('Brevo email sending failed', [
            'to' => $toEmail,
            'subject' => $subject,
            'status' => $response->status(),
            'response' => $response->json() ?? $response->body(),
        ]);

        throw new \RuntimeException(
            'Brevo API request failed with status ' . $response->status() . ': ' . $response->body()
        );
    }

    public function sendOtpEmail(string $toEmail, string $toName, int|string $otp): array
    {
        $otp = (string) $otp;
        $subject = 'NexRig - Activation Code';

        $htmlContent = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #111827;">
                <div style="padding: 24px; border: 1px solid #e5e7eb; border-radius: 12px;">
                    <h1 style="margin: 0 0 16px; font-size: 24px;">NexRig Verification Code</h1>
                    <p style="margin: 0 0 16px;">Hello ' . e($toName) . ',</p>
                    <p style="margin: 0 0 16px;">Use the code below to verify your account:</p>
                    <div style="margin: 24px 0; padding: 16px; background: #111827; color: #ffffff; font-size: 32px; font-weight: bold; text-align: center; letter-spacing: 6px; border-radius: 8px;">
                        ' . e($otp) . '
                    </div>
                    <p style="margin: 0 0 12px;">This code will expire in 10 minutes.</p>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">If you did not request this code, you can safely ignore this email.</p>
                </div>
            </div>
        ';

        $textContent = "Hello {$toName},\n\nUse this code to verify your NexRig account: {$otp}\n\nThis code will expire in 10 minutes.\n\nIf you did not request this code, you can safely ignore this email.";

        return $this->sendEmail($toEmail, $toName, $subject, $htmlContent, $textContent);
    }

    public function sendResetOtpEmail(string $toEmail, string $toName, int|string $otp): array
    {
        $otp = (string) $otp;
        $subject = 'NexRig - System Override Request';

        $htmlContent = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #111827;">
                <div style="padding: 24px; border: 1px solid #e5e7eb; border-radius: 12px;">
                    <h1 style="margin: 0 0 16px; font-size: 24px;">NexRig Password Reset Code</h1>
                    <p style="margin: 0 0 16px;">Hello ' . e($toName) . ',</p>
                    <p style="margin: 0 0 16px;">Use the code below to continue your password reset:</p>
                    <div style="margin: 24px 0; padding: 16px; background: #111827; color: #ffffff; font-size: 32px; font-weight: bold; text-align: center; letter-spacing: 6px; border-radius: 8px;">
                        ' . e($otp) . '
                    </div>
                    <p style="margin: 0 0 12px;">This code will expire in 10 minutes.</p>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">If you did not request a password reset, you can safely ignore this email.</p>
                </div>
            </div>
        ';

        $textContent = "Hello {$toName},\n\nUse this code to reset your NexRig password: {$otp}\n\nThis code will expire in 10 minutes.\n\nIf you did not request this password reset, you can safely ignore this email.";

        return $this->sendEmail($toEmail, $toName, $subject, $htmlContent, $textContent);
    }
}
