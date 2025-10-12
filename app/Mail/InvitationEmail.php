<?php

namespace App\Mail;

use App\Models\Notification;
use App\Models\Project;
use App\Models\UserInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Notification $notification)
    {
        // Достаём имя проекта, получив его id через связь morphTo с таблицей invitations.
        $project_id = $this->notification->notifiable->project_id;
        $this->project_name = Project::find($project_id)->name;

        // Достаём имя и фамилию приглашающего, получив его id через связь morphTo с таблицей invitations.
        $inviter_id = $this->notification->notifiable->inviter_id;
        $this->inviter_data = UserInfo::where('user_id', $inviter_id)->first();

        // Достаём имя и фамилию приглашаемого, получив его id через связь morphTo с таблицей invitations.
        $invitee_id = $this->notification->notifiable->invitee_id;
        $this->invitee_data = UserInfo::where('user_id', $invitee_id)->first();
    }

    public function build()
    {
        $event_type = $this->getEventType();

        return $this->subject('Приглашение в проект ' . $this->project_name)
            ->view('mail.invitation.' . $event_type)
            ->with([
                'project_name' => $this->project_name,
                'inviter' => $this->inviter_data,
                'invitee' => $this->invitee_data,
            ]);
    }

    protected function getEventType(): string
    {
        return $this->notification->event_type;
    }
}
