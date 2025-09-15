<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = url(config('app.url') . '/reset-password?token=' . $token . '&email=' . $notifiable->getEmailForPasswordReset());

            return (new MailMessage)
                ->subject('إعادة تعيين كلمة المرور')
                ->view('emails.reset-password', ['resetUrl' => $resetUrl]);
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('تأكيد البريد الإلكتروني')
                ->view('emails.verify-email', ['verificationUrl' => $url]);
        });
    }
}
