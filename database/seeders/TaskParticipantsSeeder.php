<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('task_participants')->insert([
            [
                'user_id' => 2,
                'task_id' => 1,
            ],
            [
                'user_id' => 3,
                'task_id' => 4,
            ],
            [
                'user_id' => 3,
                'task_id' => 2,
            ],
            [
                'user_id' => 4,
                'task_id' => 3,
            ],
        ]);
    }
}
