<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use YlsIdeas\FeatureFlags\Facades\Features;
use const _PHPStan_a3459023a\__;
use App\Enums\Feature;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected string $password, public string $referrer)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $autoGeneratePassword = Features::accessible(Feature::AUTO_GENERATE_PASSWORD());

        $mailMessage = (new MailMessage)
            ->subject(__('messages.emails.welcome.subject', ['app' => config('app.name')]))
            ->greeting(__('messages.emails.welcome.line1', ['notifiable' => $notifiable->name]))
            ->line(__('messages.emails.welcome.line2', ['app' => config('app.name')]));

        $autoGeneratePassword ? $mailMessage->line(__('messages.emails.welcome.line3_1')) : $mailMessage->line(__('messages.emails.welcome.line3'));
        $autoGeneratePassword ?
            $mailMessage->line(__('messages.emails.welcome.line4_1', ['password' => $this->password])) :
            $mailMessage->line(__('messages.emails.welcome.line4', ['password' => $this->password]));

        $mailMessage->action(
            __('messages.emails.welcome.action'),
            $this->referrer
        );
        return $mailMessage;
    }
}
