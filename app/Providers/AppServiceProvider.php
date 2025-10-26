<?php

namespace App\Providers;

use App\Models\Invitation;
use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectParticipant;
use App\Models\Task;
use App\Models\Tasklist;
use App\Observers\InvitationObserver;
use App\Observers\NotificationObserver;
use App\Observers\ProjectObserver;
use App\Observers\ProjectParticipantObserver;
use App\Observers\TasklistObserver;
use App\Observers\TaskObserver;
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
        Project::observe(ProjectObserver::class);
        ProjectParticipant::observe(ProjectParticipantObserver::class);
        Tasklist::observe(TasklistObserver::class);
        Task::observe(TaskObserver::class);

        Relation::morphMap([
            'invitation' => \App\Models\Invitation::class,
            'task_deadline' => \App\Models\Task::class,
        ]);
    }
}
