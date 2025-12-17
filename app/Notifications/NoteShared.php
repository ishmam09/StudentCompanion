<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoteShared extends Notification
{
    use Queueable;

    protected $sender;
    protected $note;

    public function __construct($sender, $note)
    {
        $this->sender = $sender;
        $this->note = $note;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "{$this->sender->name} shared a note: '{$this->note->title}'",
            'url' => route('notes.shared.by', $this->sender->id),
            'icon' => '📄'
        ];
    }
}
