<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Exception\TransportException;

class TestCustomMailCommand extends Command
{
    protected $signature = 'mail:test-custom
                            {email : Recipient email address}
                            {host : SMTP host}
                            {port : SMTP port}
                            {username : SMTP username/login}
                            {password : SMTP password}
                            {--scheme=smtp : Transport scheme (smtp or smtps)}
                            {--from=noreply@example.com : From email address}
                            {--name=NexRig : From name}';

    protected $description = 'Test custom SMTP settings at runtime without relying on deployed env vars';

    public function handle(MailManager $mailManager): int
    {
        $email = $this->argument('email');
        $host = $this->argument('host');
        $port = (int) $this->argument('port');
        $username = $this->argument('username');
        $password = $this->argument('password');
        $scheme = $this->option('scheme');
        $from = $this->option('from');
        $name = $this->option('name');

        if (!in_array($scheme, ['smtp', 'smtps'], true)) {
            $this->error('Invalid scheme. Use "smtp" or "smtps".');
            return self::FAILURE;
        }

        $this->info('========================================');
        $this->info('  NexRig Custom SMTP Diagnostics');
        $this->info('========================================');
        $this->newLine();
        $this->info('Runtime Mail Configuration:');
        $this->line('  MAIL_MAILER  : smtp');
        $this->line('  MAIL_HOST    : ' . $host);
        $this->line('  MAIL_PORT    : ' . $port);
        $this->line('  MAIL_SCHEME  : ' . $scheme);
        $this->line('  MAIL_USERNAME: ' . $username);
        $this->line('  MAIL_FROM    : ' . $from);
        $this->line('  MAIL_NAME    : ' . $name);
        $this->newLine();
        $this->info('Target: ' . $email);
        $this->newLine();

        $config = [
            'transport' => 'smtp',
            'scheme' => $scheme,
            'host' => $host,
            'port' => $port,
            'username' => $username,
            'password' => $password,
            'timeout' => null,
            'local_domain' => parse_url((string) config('app.url', 'http://localhost'), PHP_URL_HOST),
        ];

        try {
            $this->line('[1/3] Building runtime mailer...');
            config(['mail.mailers.runtime_test' => $config]);

            $mailer = $mailManager->mailer('runtime_test');

            $this->line('[2/3] Connecting to SMTP and sending...');
            $start = microtime(true);

            $mailer->raw(
                'This is a NexRig runtime SMTP test email. If you received this, the custom SMTP settings are working.',
                function ($message) use ($email, $from, $name) {
                    $message->to($email)
                        ->from($from, $name)
                        ->replyTo($from, $name)
                        ->subject('NexRig Custom SMTP Test');
                }
            );

            $duration = round((microtime(true) - $start) * 1000, 2);

            $this->line('[3/3] Done.');
            $this->newLine();
            $this->line('  <fg=green>✓ Test email sent successfully in ' . $duration . 'ms</>');
            $this->newLine();

            return self::SUCCESS;
        } catch (TransportException $e) {
            $this->error('✗ SMTP Transport Error');
            $this->error('  ' . $e->getMessage());
            $this->newLine();
            $this->warn('Things to check:');
            $this->warn('  1. Host and port are correct');
            $this->warn('  2. Username and password are correct');
            $this->warn('  3. Scheme matches the port (smtp for 587/2525, smtps for 465)');
            $this->warn('  4. Your hosting provider allows outbound traffic to that SMTP port');

            return self::FAILURE;
        } catch (\Throwable $e) {
            $this->error('✗ Unexpected Error');
            $this->error('  Type   : ' . get_class($e));
            $this->error('  Message: ' . $e->getMessage());
            $this->error('  File   : ' . $e->getFile() . ':' . $e->getLine());

            return self::FAILURE;
        } finally {
            app()->forgetInstance('mail.manager');
            app()->forgetInstance('mailer');
        }
    }
}
