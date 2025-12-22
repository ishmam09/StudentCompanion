<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UpcomingDeadlineNotification extends Notification
{
    use Queueable;

    public $deadline;

    /**
     * Create a new notification instance.
     */
    public function __construct($deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'deadline_id' => $this->deadline->id,
            'message' => 'Upcoming Deadline: ' . $this->deadline->title . ' is due ' . $this->deadline->due_date->diffForHumans(),
            'url' => route('deadlines.index') . '#deadline-' . $this->deadline->id,
            'icon' => '⏳',
        ];
    }
}
