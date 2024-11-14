<?php

namespace App\Notifications;

use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject("New chat message from {$this->chatMessage->user->name}")
                    ->greeting("New chat message from {$this->chatMessage->user->name}")
                    ->line(Str::limit($this->chatMessage->message, 50))
                    ->action('View Message', route('chat.index'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
