<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('departments')->insert([
        //admin
        [
            'name' => 'IT'
            
        ],
        // agent
         [
            'name' => 'Credit'
            
         ],
        //  member
         [
            'name' => 'Operations'
            
         ],
         [
            'name' => 'Business Development'
            
         ],
          [
            'name' => 'Audit'
            
        ]
       ]);
    }
}
