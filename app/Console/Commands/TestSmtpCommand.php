<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class TestSmtpCommand extends Command
{
    protected $signature = "mail:test {email?}";
    protected $description = "Test SMTP connection and send a test email";

    public function handle()
    {
        $email = $this->argument("email") ?? "test@example.com";

        $this->info("Testing SMTP Configuration...");
        $this->info("");
        $this->info("  MAIL_MAILER  : " . config("mail.default"));
        $this->info("  MAIL_HOST    : " . config("mail.mailers.smtp.host"));
        $this->info("  MAIL_PORT    : " . config("mail.mailers.smtp.port"));
        $this->info(
            "  MAIL_SCHEME  : " .
                (config("mail.mailers.smtp.scheme") ?? "(not set)"),
        );
        $this->info("  MAIL_USERNAME: " . config("mail.mailers.smtp.username"));
        $this->info("  MAIL_FROM    : " . config("mail.from.address"));
        $this->info("");

        try {
            $this->info("Sending test email to: " . $email);

            Mail::raw(
                "This is a test email from NexRig. If you received this, SMTP is working correctly.",
                function ($message) use ($email) {
                    $message->to($email)->subject("NexRig SMTP Test");
                },
            );

            $this->info("");
            $this->line("  <fg=green>✓ Email sent successfully!</>");
            $this->info("");
            return 0;
        } catch (TransportException $e) {
            $this->error("✗ SMTP Transport Error:");
            $this->error("  " . $e->getMessage());
            $this->info("");
            $this->warn("Possible causes:");
            $this->warn("  1. Wrong MAIL_HOST or MAIL_PORT");
            $this->warn(
                "  2. Railway firewall blocking port 587 — try MAIL_PORT=2525",
            );
            $this->warn("  3. Wrong MAIL_USERNAME or MAIL_PASSWORD");
            $this->warn(
                "  4. Gmail: requires a 16-char App Password, not your login password",
            );
            return 1;
        } catch (\Exception $e) {
            $this->error("✗ Unexpected Error:");
            $this->error("  Type   : " . get_class($e));
            $this->error("  Message: " . $e->getMessage());
            $this->error("  File   : " . $e->getFile() . ":" . $e->getLine());
            return 1;
        }
    }
}
