<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('branches')->insert([
        //admin
        [
            'name' => 'Head Office'
            
        ],
        // agent
         [
            'name' => 'COCIN Headquarters'
            
         ],
        //  member
         [
            'name' => 'Gindiri'
            
         ],
         [
            'name' => 'Kurgwi'
            
        ]
       ]);
    }
}
