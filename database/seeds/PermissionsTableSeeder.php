<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        array_push($data, [
            'name' => 'add administrator',
            'guard_name' => 'web'
        ]);
        array_push($data, [
            'name' => 'initiate',
            'guard_name' => 'web'
        ]);
        array_push($data, [
            'name' => 'verify',
            'guard_name' => 'web'
        ]);
        array_push($data, [
            'name' => 'authorise',
            'guard_name' => 'web'
        ]);

        DB::table('permissions')->insert($data);
    }
}
