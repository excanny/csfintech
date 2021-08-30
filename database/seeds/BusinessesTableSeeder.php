<?php

use App\Model\Business;
use App\Classes\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class BusinessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker)
    {
//        DB::table('businesses')->truncate();

        $business = Business::create([
            'name' => $faker->firstName,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber
        ]);

        $business->wallet()->create([
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => 0.0
        ]);
    }
}
