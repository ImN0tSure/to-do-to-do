<?php

namespace App\Observers;

use App\Models\ProjectParticipant;
use Illuminate\Support\Facades\Cache;

class ProjectParticipantObserver
{
    /**
     * Handle the ProjectParticipant "created" event.
     */
    public function created(ProjectParticipant $project_participant): void
    {
        $this->clearProjectCache($project_participant);
    }

    /**
     * Handle the ProjectParticipant "updated" event.
     */
    public function updated(ProjectParticipant $project_participant): void
    {
        $this->clearProjectCache($project_participant);
    }

    /**
     * Handle the ProjectParticipant "deleted" event.
     */
    public function deleted(ProjectParticipant $project_participant): void
    {
        $this->clearProjectCache($project_participant);
    }

    /**
     * Handle the ProjectParticipant "restored" event.
     */
    public function restored(ProjectParticipant $project_participant): void
    {
        //
    }

    /**
     * Handle the ProjectParticipant "force deleted" event.
     */
    public function forceDeleted(ProjectParticipant $project_participant): void
    {
        //
    }

    public function clearProjectCache(ProjectParticipant $project_participant): void {
        Cache::forget("project:{$project_participant->project_id}:participants");
    }
}
