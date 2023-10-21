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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\Semester::insert([
            ['id' => 1, 'name' => 'sem 1 2022/2023', 'section' => 'proposal'],
            ['id' => 2, 'name' => 'sem 2 2022/2023', 'section' => 'thesis'],
            // Add more entries if needed
        ]);

        // \App\Models\Student::insert([
        //     ['id' => 1, 'name' => 'sem 1 2022/2023', 'section' => 'proposal'],
        //     ['id' => 2, 'name' => 'sem 2 2022/2023', 'section' => 'thesis'],
        // ]);

    }
}
