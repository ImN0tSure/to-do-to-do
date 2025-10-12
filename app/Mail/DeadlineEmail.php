<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeadlineEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Notification $notification)
    {
        $this->task_name = $this->notification->notifiable->name;
    }

    public function build() {
        return $this->subject('Осталось менее ' . $this->notification->event_type . ' часов.')
            ->view('mail.deadline')
            ->with([
                'task_name' => $this->task_name,
                'hours_left' => $this->notification->event_type,
            ]);
    }
}
