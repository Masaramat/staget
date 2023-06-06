<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('users')->insert([
        //admin
        [
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('abc'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '111111'
        ],
        // agent
         [
            'name' => 'Chairman',
            'username' => 'chairman',
            'email' => 'chairman@gmail.com',
            'password' => Hash::make('abc'),
            'role' => 'chairman',
            'status' => 'active',
            'phone' => '222222'
         ],
        //  member
         [
            'name' => 'Member',
            'username' => 'member',
            'email' => 'member@gmail.com',
            'password' => Hash::make('abc'),
            'role' => 'member',
            'status' => 'active',
            'phone' => '333333'
        ]
       ]);
    }
}
