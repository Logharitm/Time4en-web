<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailAr extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $FrontendUrl = config('app.FrontendUrl');
        $resetUrl = url("{$FrontendUrl}/reset-password?token={$this->token}&email={$this->user->email}");
        return $this->subject('إعادة تعيين كلمة المرور')
            ->markdown('emails.reset_password_ar', [
                'user' => $this->user,
                'resetUrl' => $resetUrl,
            ]);
    }
}
