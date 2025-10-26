<?php

namespace App\Observers;

use App\Models\Tasklist;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TasklistObserver
{
    /**
     * Handle the Tasklist "created" event.
     */
    public function created(Tasklist $tasklist): void
    {
        $this->clearProjectCache($tasklist);
        Log::info("Tasklist has been created");
    }

    /**
     * Handle the Tasklist "updated" event.
     */
    public function updated(Tasklist $tasklist): void
    {
        $this->clearProjectCache($tasklist);
        Log::info("Tasklist has been updated");
    }

    /**
     * Handle the Tasklist "deleted" event.
     */
    public function deleted(Tasklist $tasklist): void
    {
        $this->clearProjectCache($tasklist);
    }

    /**
     * Handle the Tasklist "restored" event.
     */
    public function restored(Tasklist $tasklist): void
    {
        //
    }

    /**
     * Handle the Tasklist "force deleted" event.
     */
    public function forceDeleted(Tasklist $tasklist): void
    {
        //
    }

    public function clearProjectCache($tasklist): void {
        if ($project_id = $tasklist->project_id) {
            Cache::forget("project:$project_id:tasklists");
        }
    }
}
