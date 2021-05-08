<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('progress')->insert([
        	['status' => 'initial'],
        	['status' => 'progress'],
        	['status' => 'pause'],
        	['status' => 'done'],
        	['status' => 'complete'],
        ]);
    }
}
