<?php

namespace App\Listeners;

use App\Events\ChatMessageCreated;
use App\Models\User;
use App\Notifications\NewChatMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChatMessageCreatedNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChatMessageCreated $event): void
    {
        foreach (User::whereNot('id', $event->chatMessage->user_id)->cursor() as $user) {
            $user->notify(new NewChatMessage($event->chatMessage));
        }
    }
}
