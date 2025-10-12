<?php

namespace App\Observers;

use App\Jobs\SendNotificationEmail;
use App\Models\Notification;

class NotificationObserver
{
    /**
     * Handle the Notification "created" event.
     */
    public function created(Notification $notification): void
    {
        SendNotificationEmail::dispatch($notification)->afterCommit();
    }

    /**
     * Handle the Notification "updated" event.
     */
    public function updated(Notification $notification): void
    {
        //
    }

    /**
     * Handle the Notification "deleted" event.
     */
    public function deleted(Notification $notification): void
    {
        //
    }

    /**
     * Handle the Notification "restored" event.
     */
    public function restored(Notification $notification): void
    {
        //
    }

    /**
     * Handle the Notification "force deleted" event.
     */
    public function forceDeleted(Notification $notification): void
    {
        //
    }
}
