<?php

namespace App\Notifications;

use App\Services\BrevoMailService;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['brevo'];
    }

    /**
     * Send the notification via Brevo
     */
    public function toBrevo($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $brevoService = new BrevoMailService();
        $name = $notifiable->name ?? 'User';
        
        $brevoService->sendPasswordResetEmail(
            $notifiable->getEmailForPasswordReset(),
            $name,
            $resetUrl
        );
    }
}
