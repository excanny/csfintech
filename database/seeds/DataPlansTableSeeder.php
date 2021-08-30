<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('data_plans')->truncate();
    }
}
