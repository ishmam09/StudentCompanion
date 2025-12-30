<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewConsultationRequest extends Notification
{
    use Queueable;

    public $consultation_id;
    public $student_name;
    public $topic;

    public function __construct($consultation_id, $student_name, $topic)
    {
        $this->consultation_id = $consultation_id;
        $this->student_name = $student_name;
        $this->topic = $topic;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'consultation_request', // Custom type key for view logic
            'consultation_id' => $this->consultation_id,
            'message' => "New consultation request from {$this->student_name}: {$this->topic}",
            'url' => '#', // No direct link, handled by buttons
            'icon' => '📅',
            'action_url_accept' => route('consultations.status', ['id' => $this->consultation_id, 'status' => 'approved']),
            'action_url_reject' => route('consultations.status', ['id' => $this->consultation_id, 'status' => 'rejected']),
        ];
    }
}
