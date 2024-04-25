<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{

    private Request $request;

    public function __construct($token, Request $request)
    {
        parent::__construct($token);

        $this->request = $request;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(sprintf('%s: %s', config('app.name'), __('Reset Password')))
            ->line(
                __('Hello :user, Kindly check the following link, & follow the steps to reset your password on :app ', [
                    'user' => $notifiable->name,
                    'app' => config('app.name'),
                ])
            )
            ->action('Reset Password', url($this->request->headers->get('referer') . 'auth/reset-password?' . http_build_query([
                    'token' => $this->token,
                    'email' => $notifiable->email,
                ])))
            ->line('If you did not request a password reset, no further action is required.');
    }

}
