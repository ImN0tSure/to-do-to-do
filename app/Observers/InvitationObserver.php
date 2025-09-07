<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Models\Notification;

class InvitationObserver
{
    /**
     * Handle the Invitation "created" event.
     */
    public function created(Invitation $invitation): void
    {
        Notification::create([
            'user_id' => $invitation->invitee_id,
            'notifiable_type' => 'invitation',
            'notifiable_id' => $invitation->id,
            'event' => 'invitation',
            'event_type' => 'created'
        ]);
    }

    /**
     * Handle the Invitation "updated" event.
     */
    public function updated(Invitation $invitation): void
    {
        $event_type = $invitation->is_accepted ? 'accepted' : 'declined';

        Notification::create([
            'user_id' => $invitation->inviter_id,
            'notifiable_type' => 'invitation',
            'notifiable_id' => $invitation->id,
            'event' => 'invitation',
            'event_type' => $event_type,
        ]);
    }

    /**
     * Handle the Invitation "deleted" event.
     */
    public function deleted(Invitation $invitation): void
    {
        //
    }

    /**
     * Handle the Invitation "restored" event.
     */
    public function restored(Invitation $invitation): void
    {
        //
    }

    /**
     * Handle the Invitation "force deleted" event.
     */
    public function forceDeleted(Invitation $invitation): void
    {
        //
    }
}
