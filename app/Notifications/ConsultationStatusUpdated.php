<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ConsultationStatusUpdated extends Notification
{
    use Queueable;

    public $teacher_name;
    public $status;

    public function __construct($teacher_name, $status)
    {
        $this->teacher_name = $teacher_name;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $icon = $this->status === 'approved' ? '✅' : '❌';
        return [
            'type' => 'status_update',
            'message' => "Your appointment with {$this->teacher_name} is {$this->status}.",
            'url' => route('consultations.index'),
            'icon' => $icon,
        ];
    }
}
