<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('project_participants')->insert([
            [
                'user_id' => 1,
                'project_id' => 1,
                'status' => '0',
                'role'=> 'creator',
            ],
            [
                'user_id' => 1,
                'project_id' => 2,
                'status' => '0',
                'role'=> 'creator',
            ],
            [
                'user_id' => 2,
                'project_id' => 1,
                'status' => '2',
                'role'=> 'executor',
            ],
            [
                'user_id' => 3,
                'project_id' => 1,
                'status' => '2',
                'role'=> 'executor',
            ],
            [
                'user_id' => 5,
                'project_id' => 1,
                'status' => '1',
                'role'=> 'curator',
            ],

            [
                'user_id' => 6,
                'project_id' => 2,
                'status' => '1',
                'role'=> 'curator',
            ],
        ]);
    }
}
