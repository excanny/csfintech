<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KycTableSeeder extends Seeder
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
           'name' => 'BVN without image',
           'slug' => 'bvn',
           'description' => 'Verify BVN identities of various users',
           'cost' => '9'
        ]);

        array_push($data, [
           'name' => 'BVN with Image',
           'slug' => 'ibvn',
           'description' => 'Verify BVN identities of various users with their images',
           'cost' => '35'
        ]);

        array_push($data, [
           'name' => 'National Identity Number',
           'slug' => 'nin',
           'description' => 'Verify NIN identities of various users',
           'cost' => '95'
        ]);

        array_push($data, [
           'name' => 'Personal Voters Card',
           'slug' => 'pvc',
           'description' => 'Verify PVC identities of various users',
           'cost' => '95'
        ]);

        array_push($data, [
           'name' => 'FRSC Driver\'s License',
           'slug' => 'dl',
           'description' => 'Verify Drivers License identities of various users',
           'cost' => '95'
        ]);

        array_push($data, [
           'name' => 'NIS\'s Passport with image',
           'slug' => 'nip',
           'description' => 'Verify NIP identities of various users',
           'cost' => '95'
        ]);

        DB::table('kyc')->truncate();
        DB::table('kyc')->insert($data);
    }
}
