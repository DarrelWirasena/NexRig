<?php

namespace App\Console\Commands;

use App\Services\BrevoMailService;
use Illuminate\Console\Command;

class TestBrevoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-brevo {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a test email using the BrevoMailService to verify API credentials.';

    /**
     * Execute the console command.
     */
    public function handle(BrevoMailService $brevoMailService)
    {
        $email = $this->argument('email');
        $name = 'Brevo Test';
        $otp = random_int(100000, 999999);

        $this->info("Attempting to send a test email to {$email} via Brevo...");
        $this->info('Using sender: ' . config('services.brevo.sender_email'));

        try {
            $result = $brevoMailService->sendOtpEmail($email, $name, $otp);

            if ($result['success']) {
                $this->info('✅ Email sent successfully!');
                $this->line('   Message ID: ' . ($result['message_id'] ?? 'N/A'));
                $this->comment('Please check the inbox for ' . $email . ' to confirm receipt.');
            } else {
                $this->error('Email sending failed. The API responded, but indicated failure.');
                $this->line('Response: ' . json_encode($result['response'] ?? [], JSON_PRETTY_PRINT));
            }

        } catch (\Exception $e) {
            $this->error('✗ An exception occurred while trying to send the email:');
            $this->error($e->getMessage());
            $this->line('Please check your application logs on Railway for the full stack trace.');
            $this->warn('Ensure your BREVO_API_KEY and BREVO_SENDER_EMAIL are set correctly in your Railway environment variables.');
            return 1;
        }

        return 0;
    }
}
