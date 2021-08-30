<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
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
            'slug' => 'airtime',
            'name' => 'AIRTIME'
        ]);

        array_push($data, [
            'slug' => 'data',
            'name' => 'DATA'
        ]);

//        array_push($data, [
//            'slug' => 'bill_payment',
//            'name' => 'BILL PAYMENT'
//        ]);

        array_push($data, [
            'slug' => 'transfer',
            'name' => 'TRANSFER'
        ]);

        array_push($data, [
            'slug' => 'electricity',
            'name' => 'ELECTRICITY'
        ]);

        array_push($data, [
            'slug' => 'cable_tv',
            'name' => 'CABLE TV'
        ]);

        array_push($data, [
            'slug' => 'payment_gateway',
            'name' => 'PAYMENT GATEWAY'
        ]);

//        array_push($data, [
//            'slug' => 'cash_out',
//            'name' => 'CASH OUT'
//        ]);

//        array_push($data, [
//            'slug' => 'e_wallet',
//            'name' => 'E WALLET'
//        ]);
//        array_push($data, [
//            'slug' => 'collection_api',
//            'name' => 'COLLECTION API'
//        ]);

        DB::table('products')->truncate();
        DB::table('products')->insert( $data );
    }
}
