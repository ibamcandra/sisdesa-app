<?php

namespace App\Providers;

use App\Models\EmailConfig;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');

        // Load dynamic email configuration from database
        try {
            if (!app()->runningInConsole() && Schema::hasTable('email_configs')) {
                $mailConfig = EmailConfig::first();
                
                if ($mailConfig) {
                    $config = [
                        'driver' => $mailConfig->mail_mailer ?? 'smtp',
                        'host' => $mailConfig->mail_host,
                        'port' => $mailConfig->mail_port,
                        'from' => [
                            'address' => $mailConfig->mail_from_address,
                            'name' => $mailConfig->mail_from_name,
                        ],
                        'encryption' => $mailConfig->mail_encryption,
                        'username' => $mailConfig->mail_username,
                        'password' => $mailConfig->mail_password,
                    ];

                    Config::set('mail.mailers.smtp', array_merge(config('mail.mailers.smtp'), $config));
                    Config::set('mail.from', $config['from']);
                }
            }
        } catch (\Throwable $e) {
            // Ignore DB errors during boot
        }
    }
}
