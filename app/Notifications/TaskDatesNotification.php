<?php

namespace App\Notifications;

use App\Enums\TaskDatesNotificationTypes;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class TaskDatesNotification extends Notification
{
    use Queueable;

    private $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Task $task, TaskDatesNotificationTypes $type)
    {
        $this->type = $type;
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
        $shortTaskName = Str::limit($this->task->title, 20);

        $message = (new MailMessage)->subject($this->type === TaskDatesNotificationTypes::START
            ? "Start date reached"
            : "Due date reached"
        )->line($this->type === TaskDatesNotificationTypes::START
            ? "The {$shortTaskName} task should be started today"
            : "The {$shortTaskName} task should be finished today"
        )->action('View Task', route('tasks.show', $this->task->id));

        return $message;
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
