<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        //For Airtime
        array_push($data, [
            'name' => 'SONITE',
            'slug' => 'sonite',
            'product' => 'AIRTIME',
            'status' => 'ACTIVE'
        ]);
        array_push($data, [
            'name' => 'SHAGO',
            'slug' => 'shago',
            'product' => 'AIRTIME',
            'status' => 'INACTIVE'
        ]);
        array_push($data, [
            'name' => 'CAPRICORN',
            'slug' => 'capricorn',
            'product' => 'AIRTIME',
            'status' => 'INACTIVE'
        ]);


        //For Data
        array_push($data, [
            'name' => 'SONITE',
            'slug' => 'sonite',
            'product' => 'DATA',
            'status' => 'ACTIVE'
        ]);
        array_push($data, [
            'name' => 'SHAGO',
            'slug' => 'shago',
            'product' => 'DATA',
            'status' => 'INACTIVE'
        ]);
        array_push($data, [
            'name' => 'CAPRICORN',
            'slug' => 'capricorn',
            'product' => 'DATA',
            'status' => 'INACTIVE'
        ]);


        //For Cable TV
        array_push($data, [
            'name' => 'SHAGO',
            'slug' => 'shago',
            'product' => 'CABLETV',
            'status' => 'INACTIVE'
        ]);
        array_push($data, [
            'name' => 'CAPRICORN',
            'slug' => 'capricorn',
            'product' => 'CABLETV',
            'status' => 'INACTIVE'
        ]);


        //For Electricity
        array_push($data, [
            'name' => 'SHAGO',
            'slug' => 'shago',
            'product' => 'ELECTRICITY',
            'status' => 'INACTIVE'
        ]);
        array_push($data, [
            'name' => 'CAPRICORN',
            'slug' => 'capricorn',
            'product' => 'ELECTRICITY',
            'status' => 'INACTIVE'
        ]);


        DB::table('providers')->truncate();
        DB::table('providers')->insert($data);
    }
}
