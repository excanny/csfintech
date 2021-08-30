<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

//        array_push($data, [
//            'name' => 'SUPER_ADMIN',
//            'guard_name' => 'web'
//        ]);
//        array_push($data, [
//            'name' => 'ADMIN',
//            'guard_name' => 'web'
//        ]);
//        array_push($data, [
//            'name_name' => 'MERCHANT',
//            'guard_name' => 'web'
//        ]);
//        array_push($data, [
//            'name' => 'USER',
//            'guard_name' => 'web'
//        ]);
        array_push($data, [
            'name' => 'MERCHANT_ADMIN',
            'guard_name' => 'web'
        ]);

        DB::table('roles')->insert($data);

    }
}
