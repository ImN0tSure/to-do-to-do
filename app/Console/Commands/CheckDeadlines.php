<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class CheckDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check tasks deadlines. Creating notifications for user when remains less than 48 and 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        Task::where('end_date', '<=', $now->copy()->addHours(48))
            ->where('end_date', '>=', $now->copy()->addHours(24))
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'deadline_48h');
            })
            ->each(function ($task) {
                $task->createDeadlineNotification('deadline_48h');
            });

        Task::where('end_date', '<=', $now->copy()->addHours(24))
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'deadline_24h');
            })
            ->each(function ($task) {
                $task->createDeadlineNotification('deadline_24h');
            });
    }

}
