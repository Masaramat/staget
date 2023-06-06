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
        $this->call(UsersTableSeeder::class);
        \App\Models\User::factory(4)->create();

        $this->call(StatesTableSeeder::class);

        $this->call(LocalGovernmentsTableSeeder::class);
        $this->call(BranchesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);

        

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
