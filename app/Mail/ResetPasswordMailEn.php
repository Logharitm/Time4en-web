<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailEn extends Mailable
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
        $resetUrl = url("/reset-password?token={$this->token}&email={$this->user->email}");

        return $this->subject('Reset Your Password')
            ->markdown('emails.reset_password_en', [
                'user' => $this->user,
                'resetUrl' => $resetUrl,
            ]);
    }
}
