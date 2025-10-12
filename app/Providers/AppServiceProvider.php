<?php

namespace App\Providers;

use App\Models\Invitation;
use App\Models\Notification;
use App\Observers\InvitationObserver;
use App\Observers\NotificationObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Invitation::observe(InvitationObserver::class);
        Notification::observe(NotificationObserver::class);

        Relation::morphMap([
            'invitation' => \App\Models\Invitation::class,
            'task_deadline' => \App\Models\Task::class,
        ]);
    }
}
