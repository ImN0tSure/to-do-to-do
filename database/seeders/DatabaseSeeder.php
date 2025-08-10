<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Userold::factory(10)->create();

        // \App\Models\Userold::factory()->create([
        //     'name' => 'Test Userold',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            UsersSeeder::class,
            UserInfosSeeder::class,
            ProjectsSeeder::class,
            TasklistSeeder::class,
            TasksSeeder::class,
            TaskParticipantsSeeder::class,
            ProjectParticipantsSeeder::class,
            CommentsSeeder::class,
        ]);
    }
}
