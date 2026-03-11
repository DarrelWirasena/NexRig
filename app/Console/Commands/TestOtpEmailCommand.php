<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class TestOtpEmailCommand extends Command
{
    protected $signature = "mail:test-otp {email}";
    protected $description = "Test sending an OTP email with full diagnostics";

    public function handle()
    {
        $email = $this->argument("email");
        $otp = random_int(100000, 999999);

        $this->info("========================================");
        $this->info("  NexRig OTP Email Diagnostics");
        $this->info("========================================");
        $this->info("");
        $this->info("Mail Configuration:");
        $this->info("  MAIL_MAILER  : " . config("mail.default"));
        $this->info("  MAIL_HOST    : " . config("mail.mailers.smtp.host"));
        $this->info("  MAIL_PORT    : " . config("mail.mailers.smtp.port"));
        $this->info(
            "  MAIL_SCHEME  : " .
                (config("mail.mailers.smtp.scheme") ?? "(not set)"),
        );
        $this->info("  MAIL_USERNAME: " . config("mail.mailers.smtp.username"));
        $this->info("  MAIL_FROM    : " . config("mail.from.address"));
        $this->info("  MAIL_NAME    : " . config("mail.from.name"));
        $this->info("");
        $this->info("Target  : " . $email);
        $this->info("Test OTP: " . $otp);
        $this->info("");

        if (config("mail.default") === "log") {
            $this->warn('⚠  MAIL_MAILER is set to "log".');
            $this->warn(
                "   Emails will be written to the log file, NOT actually sent.",
            );
            $this->warn(
                "   Set MAIL_MAILER=smtp in your Railway environment variables.",
            );
            $this->info("");
        }

        try {
            $this->line("[1/3] Building OtpMail...");
            $mailable = new OtpMail($otp);

            $this->line("[2/3] Connecting to SMTP and sending...");
            $start = microtime(true);
            Mail::to($email)->send($mailable);
            $duration = round((microtime(true) - $start) * 1000, 2);

            $this->line("[3/3] Done.");
            $this->info("");
            $this->line(
                "  <fg=green>✓ OTP email sent successfully in " .
                    $duration .
                    "ms</>",
            );
            $this->info("");
            $this->info("Check your inbox at: " . $email);
            return 0;
        } catch (TransportException $e) {
            $this->error("✗ SMTP Transport Error");
            $this->error("  " . $e->getMessage());
            $this->info("");
            $this->warn("Possible fixes:");
            $this->warn(
                "  1. Railway may be blocking port 587 — try MAIL_PORT=2525",
            );
            $this->warn(
                "  2. Double-check MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD",
            );
            $this->warn(
                "  3. Gmail requires a 16-char App Password, not your login password",
            );
            $this->warn(
                "  4. Switch to Mailtrap (live.smtp.mailtrap.io) for reliability",
            );
            return 1;
        } catch (\Exception $e) {
            $this->error("✗ Failed to send OTP email");
            $this->error("");
            $this->error("  Error Type : " . get_class($e));
            $this->error("  Message    : " . $e->getMessage());
            $this->error(
                "  File       : " . $e->getFile() . ":" . $e->getLine(),
            );
            $this->info("");
            $this->warn("Possible fixes:");
            $this->warn(
                "  1. Verify MAIL_USERNAME and MAIL_PASSWORD in Railway env vars",
            );
            $this->warn(
                "  2. For Gmail: use a 16-char App Password (not your login password)",
            );
            $this->warn(
                "  3. Check if Railway firewall allows your chosen MAIL_PORT",
            );
            $this->warn(
                "  4. Consider switching to Mailtrap: https://mailtrap.io",
            );
            return 1;
        }
    }
}
