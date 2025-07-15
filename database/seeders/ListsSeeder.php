<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lists')->insert([
            [
                'name' => 'Не начато',
                'description' => Str::random(50),
                'project_id' => 1,
            ],
            [
                'name' => 'В работе',
                'description' => Str::random(50),
                'project_id' => 1,
            ],
            [
                'name' => 'Завершено',
                'description' => Str::random(50),
                'project_id' => 1,
            ],
            [
                'name' => 'Не начато',
                'description' => Str::random(50),
                'project_id' => 2,
            ],
            [
                'name' => 'В работе',
                'description' => Str::random(50),
                'project_id' => 2,
            ],
            [
                'name' => 'Завершено',
                'description' => Str::random(50),
                'project_id' => 2,
            ],
        ]);
    }
}
