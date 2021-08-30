<?php

use Illuminate\Database\Seeder;

class BillersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('billers')->truncate();
        DB::table('biller_items')->truncate();
        $dstv = \App\Model\Biller::create([
            'biller_type' => 'CABLE-TV',
            'category_name' => 'Cable TV',
            'provider' => 'SHAGO',
            'biller_name' => 'DSTV',
            'narration' => 'DSTV Subscription',
            'short_name' => '',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/dstv.webp',
            'type' => ''
        ]);

        $gotv = \App\Model\Biller::create([
            'biller_type' => 'CABLE-TV',
            'category_name' => 'Cable TV',
            'provider' => 'SHAGO',
            'biller_name' => 'GOTV',
            'narration' => 'GOTV Subscription',
            'short_name' => '',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/gotv.webp',
            'type' => ''
        ]);

        $startimes = \App\Model\Biller::create([
            'biller_type' => 'CABLE-TV',
            'category_name' => 'Cable TV',
            'provider' => 'SHAGO',
            'biller_name' => 'STARTIMES',
            'narration' => 'STARTIMES Subscription',
            'short_name' => '',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/startimes.webp',
            'type' => ''
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Abuja Prepaid',
            'narration' => 'Abuja electricity bill payment',
            'short_name' => 'AEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/aedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Abuja Postpaid',
            'narration' => 'Abuja electricity bill payment',
            'short_name' => 'AEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/aedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kaduna Prepaid',
            'narration' => 'Kaduna electricity bill payment',
            'short_name' => 'KAEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kaduna-electric.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kaduna Postpaid',
            'narration' => 'Kaduna electricity bill payment',
            'short_name' => 'KAEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kaduna-electric.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Jos Prepaid',
            'narration' => 'Jos electricity bill payment',
            'short_name' => 'JEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/jedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Jos Postpaid',
            'narration' => 'Jos electricity bill payment',
            'short_name' => 'JEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/jedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ikeja Prepaid',
            'narration' => 'Ikeja electricity bill payment',
            'short_name' => 'IKEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ikedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ikeja Postpaid',
            'narration' => 'Ikeja electricity bill payment',
            'short_name' => 'IKEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ikedc.webp',
            'type' => 'POSTPAID'
        ]);
        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Eko Prepaid',
            'narration' => 'Eko electricity bill payment',
            'short_name' => 'EKEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ekedc.webp',
            'type' => 'PREPAID'
        ]);
        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Eko Postpaid',
            'narration' => 'Eko electricity bill payment',
            'short_name' => 'EKEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ekedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kano Prepaid',
            'narration' => 'Kano electricity bill payment',
            'short_name' => 'KEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kedco.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kano Postpaid',
            'narration' => 'Kano electricity bill payment',
            'short_name' => 'KEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kedco.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Enugu Prepaid',
            'narration' => 'Enugu electricity bill payment',
            'short_name' => 'EEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/eedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Enugu Postpaid',
            'narration' => 'Enugu electricity bill payment',
            'short_name' => 'EEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/eedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Phort-Harcout Prepaid',
            'narration' => 'Phort-Harcout electricity bill payment',
            'short_name' => 'PHEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/phedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Phort-Harcout Postpaid',
            'narration' => 'Phort-Harcout electricity bill payment',
            'short_name' => 'PHEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/phedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ibadan Prepaid',
            'narration' => 'Ibadan electricity bill payment',
            'short_name' => 'IBEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ibedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ibadan Postpaid',
            'narration' => 'Ibadan electricity bill payment',
            'short_name' => 'IBEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ibedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Abuja Prepaid',
            'narration' => 'Abuja electricity bill payment',
            'short_name' => 'AEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/aedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Abuja Postpaid',
            'narration' => 'Abuja electricity bill payment',
            'short_name' => 'AEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/aedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kaduna Prepaid',
            'narration' => 'Kaduna electricity bill payment',
            'short_name' => 'KAEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kaduna-electric.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kaduna Postpaid',
            'narration' => 'Kaduna electricity bill payment',
            'short_name' => 'KAEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kaduna-electric.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Jos Prepaid',
            'narration' => 'Jos electricity bill payment',
            'short_name' => 'JEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/jedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Jos Postpaid',
            'narration' => 'Jos electricity bill payment',
            'short_name' => 'JEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/jedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ikeja Prepaid',
            'narration' => 'Ikeja electricity bill payment',
            'short_name' => 'IKEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ikedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ikeja Postpaid',
            'narration' => 'Ikeja electricity bill payment',
            'short_name' => 'IKEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ikedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kano Prepaid',
            'narration' => 'Kano electricity bill payment',
            'short_name' => 'KEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kedco.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kano Postpaid',
            'narration' => 'Kano electricity bill payment',
            'short_name' => 'KEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kedco.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Enugu Prepaid',
            'narration' => 'Enugu electricity bill payment',
            'short_name' => 'EEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/eedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Enugu Postpaid',
            'narration' => 'Enugu electricity bill payment',
            'short_name' => 'EEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/eedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Phort-Harcout Prepaid',
            'narration' => 'Phort-Harcout electricity bill payment',
            'short_name' => 'PHEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/phedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Phort-Harcout Postpaid',
            'narration' => 'Phort-Harcout electricity bill payment',
            'short_name' => 'PHEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/phedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ibadan Prepaid',
            'narration' => 'Ibadan electricity bill payment',
            'short_name' => 'IBEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ibedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ibadan Postpaid',
            'narration' => 'Ibadan electricity bill payment',
            'short_name' => 'IBEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ibedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Abuja Prepaid',
            'narration' => 'Abuja electricity bill payment',
            'short_name' => 'AEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/aedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Abuja Postpaid',
            'narration' => 'Abuja electricity bill payment',
            'short_name' => 'AEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/aedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kaduna Prepaid',
            'narration' => 'Kaduna electricity bill payment',
            'short_name' => 'KAEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kaduna-electric.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kaduna Postpaid',
            'narration' => 'Kaduna electricity bill payment',
            'short_name' => 'KAEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kaduna-electric.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Jos Prepaid',
            'narration' => 'Jos electricity bill payment',
            'short_name' => 'JEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/jedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Jos Postpaid',
            'narration' => 'Jos electricity bill payment',
            'short_name' => 'JEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/jedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ikeja Prepaid',
            'narration' => 'Ikeja electricity bill payment',
            'short_name' => 'IKEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ikedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ikeja Postpaid',
            'narration' => 'Ikeja electricity bill payment',
            'short_name' => 'IKEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ikedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kano Prepaid',
            'narration' => 'Kano electricity bill payment',
            'short_name' => 'KEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kedco.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Kano Postpaid',
            'narration' => 'Kano electricity bill payment',
            'short_name' => 'KEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/kedco.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Enugu Prepaid',
            'narration' => 'Enugu electricity bill payment',
            'short_name' => 'EEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/eedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Enugu Postpaid',
            'narration' => 'Enugu electricity bill payment',
            'short_name' => 'EEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/eedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Phort-Harcout Prepaid',
            'narration' => 'Phort-Harcout electricity bill payment',
            'short_name' => 'PHEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/phedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Phort-Harcout Postpaid',
            'narration' => 'Phort-Harcout electricity bill payment',
            'short_name' => 'PHEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/phedc.webp',
            'type' => 'POSTPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ibadan Prepaid',
            'narration' => 'Ibadan electricity bill payment',
            'short_name' => 'IBEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ibedc.webp',
            'type' => 'PREPAID'
        ]);

        \App\Model\Biller::create([
            'biller_type' => 'ELECTRICITY',
            'category_name' => 'Electricity Bill',
            'provider' => 'SHAGO',
            'biller_name' => 'Ibadan Postpaid',
            'narration' => 'Ibadan electricity bill payment',
            'short_name' => 'IBEDC',
            'charge' => '50',
            'logo_url' => 'assets/images/billers/ibedc.webp',
            'type' => 'POSTPAID'
        ]);

        $glo = \App\Model\Biller::create([
            'biller_type' => 'DATA',
            'category_name' => 'Data',
            'provider' => 'SHAGO',
            'biller_name' => 'GLO',
            'narration' => 'GLO DATA Subscription',
            'short_name' => '',
            'charge' => '',
            'logo_url' => 'assets/images/billers/glo.webp',
            'type' => 'GLODATA'
        ]);
        $mtn = \App\Model\Biller::create([
            'biller_type' => 'DATA',
            'category_name' => 'Data',
            'provider' => 'SHAGO',
            'biller_name' => 'MTN',
            'narration' => 'MTN DATA Subscription',
            'short_name' => '',
            'charge' => '',
            'logo_url' => 'assets/images/billers/mtn.webp',
            'type' => 'MTNDATA'
        ]);
        $airtel = \App\Model\Biller::create([
            'biller_type' => 'DATA',
            'category_name' => 'Data',
            'provider' => 'SHAGO',
            'biller_name' => 'AIRTEL',
            'narration' => 'AIRTEL DATA Subscription',
            'short_name' => '',
            'charge' => '',
            'logo_url' => 'assets/images/billers/airtel.webp',
            'type' => 'AIRTELDATA'
        ]);
        $etisalat = \App\Model\Biller::create([
            'biller_type' => 'DATA',
            'category_name' => 'Data',
            'provider' => 'SHAGO',
            'biller_name' => 'ETISALAT',
            'narration' => 'ETISALAT DATA Subscription',
            'short_name' => '',
            'charge' => '',
            'logo_url' => 'assets/images/billers/etisalat.webp',
            'type' => '9MOBILEDATA'
        ]);


        \App\Model\BillerItem::create([
            'biller_id' => $dstv->id,
            'name' => 'DStv Compact',
            'amount' => '7900',
            'code' => 'COMPE36',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $dstv->id,
            'name' => 'DStv Compact Plus',
            'amount' => '12400',
            'code' => 'COMPLE36',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $dstv->id,
            'name' => 'DStv Premium',
            'amount' => '18400',
            'code' => 'PRWE36',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $dstv->id,
            'name' => 'DStv Premium Asia',
            'amount' => '20500',
            'code' => 'PRWASIE36',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $dstv->id,
            'name' => 'Asian Bouqet',
            'amount' => '6200',
            'code' => 'ASIAE36',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $dstv->id,
            'name' => 'DStv Yanga Bouquet E36',
            'amount' => '2565',
            'code' => 'NNJ1E36',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $dstv->id,
            'name' => 'DStv Confam Bouquet E36',
            'amount' => '4615',
            'code' => 'NNJ2E36',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $dstv->id,
            'name' => 'Padi',
            'amount' => '1850',
            'code' => 'NLTESE36',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $gotv->id,
            'name' => 'GOtv Max',
            'amount' => '3600',
            'code' => 'GOTVMAX',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $gotv->id,
            'name' => 'GOtv Jinja Bouquet',
            'amount' => '1640',
            'code' => 'GOTVNJ1',
        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $gotv->id,
            'name' => 'GOtv Jolli Bouquet',
            'amount' => '2460',
            'code' => 'GOTVNJ2',
        ]);

//        \App\Model\BillerItem::create([
//            'biller_id' => $gotv->id,
//            'name' => 'GOtv Lite',
//            'amount' => '410',
//            'code' => 'GOLITE',
//        ]);
//
//        \App\Model\BillerItem::create([
//            'biller_id' => $gotv->id,
//            'name' => 'GOtv Max',
//            'amount' => '3600',
//            'code' => 'GOtvMax',
//        ]);
//
//        \App\Model\BillerItem::create([
//            'biller_id' => $gotv->id,
//            'name' => 'GOtv Jinja Bouquet',
//            'amount' => '1640',
//            'code' => 'GOTVNJ1',
//        ]);
//
//        \App\Model\BillerItem::create([
//            'biller_id' => $gotv->id,
//            'name' => 'GOtv Jolli Bouquet',
//            'amount' => '2460',
//            'code' => 'GOTVNJ2',
//        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $gotv->id,
            'name' => 'GOtv Smallie - monthly',
            'amount' => '800',
            'code' => 'GOHAN',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $gotv->id,
            'name' => 'GOtv Smallie - quarterly',
            'amount' => '2100',
            'code' => 'GOLITE',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $gotv->id,
            'name' => 'GOtv Smallie - yearly',
            'amount' => '6200',
            'code' => 'GOLTANL',
        ]);

//        \App\Model\BillerItem::create([
//            'biller_id' => $gotv->id,
//            'name' => 'GOtv Lite',
//            'amount' => '410',
//            'code' => 'GOLITE',
//        ]);

        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '50',
            'code' => 'GD2',
            'allowance' => '50MB',
            'duration' => '1Day',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '100',
            'code' => 'GD3',
            'allowance' => '150MB',
            'duration' => '1Day',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '200',
            'code' => 'GD4',
            'allowance' => '350MB',
            'duration' => '2Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '500',
            'code' => 'GD5',
            'allowance' => '1.35GB',
            'duration' => '14Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '1000',
            'code' => 'GD6',
            'allowance' => '2.9GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '1500',
            'code' => 'GD7',
            'allowance' => '4.1GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '2000',
            'code' => 'GD8',
            'allowance' => '5.8GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '2500',
            'code' => 'GD9',
            'allowance' => '7.7GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '3000',
            'code' => 'GL1',
            'allowance' => '10GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '4000',
            'code' => 'GL2',
            'allowance' => '13.25GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '5000',
            'code' => 'GL3',
            'allowance' => '18.25GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '8000',
            'code' => 'GL4',
            'allowance' => '29.5GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '10000',
            'code' => 'GL5',
            'allowance' => '50GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '15000',
            'code' => 'GL6',
            'allowance' => '93GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '18000',
            'code' => 'GL7',
            'allowance' => '119GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '20000',
            'code' => 'GL8',
            'allowance' => '138GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '25',
            'code' => 'GL9',
            'allowance' => '250MB',
            'duration' => '1Day',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '50',
            'code' => 'GO1',
            'allowance' => '500MB',
            'duration' => '1Day',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '100',
            'code' => 'GO2',
            'allowance' => '1GB',
            'duration' => '5Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '1500',
            'code' => 'GO3',
            'allowance' => '7GB',
            'duration' => '7Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $glo->id,
            'amount' => '200',
            'code' => 'GO4',
            'allowance' => '1.25GB',
            'duration' => '1Day',
        ]);


        // 9MOBILE BUNDLES
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '50',
            'code' => 'ET1',
            'allowance' => '25MB',
            'duration' => '1Day',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '100',
            'code' => 'ET2',
            'allowance' => '100MB',
            'duration' => '1Day',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '200',
            'code' => 'ET5',
            'allowance' => '650MB',
            'duration' => '1Day',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '1000',
            'code' => 'ET9',
            'allowance' => '1GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '1200',
            'code' => 'ES4',
            'allowance' => '1.5GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '2000',
            'code' => 'ES5',
            'allowance' => '2.5GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '3000',
            'code' => 'ES6',
            'allowance' => '4GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '4000',
            'code' => 'ES8',
            'allowance' => '5.5GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '8000',
            'code' => 'ES9',
            'allowance' => '11.5GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '10000',
            'code' => 'EL1',
            'allowance' => '15GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '18000',
            'code' => 'EL2',
            'allowance' => '27.5GB',
            'duration' => '30Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '25000',
            'code' => 'EL3',
            'allowance' => '75GB',
            'duration' => '90Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '50000',
            'code' => 'EL4',
            'allowance' => '165GB',
            'duration' => '180Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '84992',
            'code' => 'EL5',
            'allowance' => '100GB',
            'duration' => '100Days',
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $etisalat->id,
            'amount' => '100000',
            'code' => 'EL7',
            'allowance' => '365GB',
            'duration' => '365Days',
        ]);


        // MTN BUNDLES
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount" => "100",
            "code" => "MT1",
            "allowance" => "100MB",
            "duration" => "1Day"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "200",
            "code"=> "MT2",
            "allowance"=> "200MB",
            "duration"=> "1Day"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "300",
            "code"=> "MT3",
            "allowance"=> "350MB",
            "duration"=> "7Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "500",
            "code"=> "MT4",
            "allowance"=> "750MB",
            "duration"=> "7Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "1200",
            "code"=> "MT5",
            "allowance"=> "2GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "1000",
            "code"=> "MT6",
            "allowance"=> "1.5GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "25",
            "code"=> "MT7",
            "allowance"=> "20MB",
            "duration"=> "24Hrs"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "50",
            "code"=> "MT8",
            "allowance"=> "50MB",
            "duration"=> "7Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "150",
            "code"=> "MT9",
            "allowance"=> "160MB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "350",
            "code"=> "MN1",
            "allowance"=> "1GB",
            "duration"=> "1Day"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "1500",
            "code"=> "MN3",
            "allowance"=> "3GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "100000",
            "code"=> "MN4",
            "allowance"=> "325GB",
            "duration"=> "180Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "300000",
            "code"=> "MN5",
            "allowance"=> "1000GB",
            "duration"=> "1-Year"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "450000",
            "code"=> "MN6",
            "allowance"=> "1500GB",
            "duration"=> "1-Year"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "500",
            "code"=> "MN7",
            "allowance"=> "1GB",
            "duration"=> "2Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "500",
            "code"=> "MN8",
            "allowance"=> "2.5GB",
            "duration"=> "2Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "120000",
            "code"=> "MN9",
            "allowance"=> "400GB",
            "duration"=> "1-Year"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "20000",
            "code"=> "MM1",
            "allowance"=> "75GB",
            "duration"=> "60Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "30000",
            "code"=> "MM2",
            "allowance"=> "120GB",
            "duration"=> "60Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "50000",
            "code"=> "MM3",
            "allowance"=> "150GB",
            "duration"=> "90Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "75000",
            "code"=> "MM4",
            "allowance"=> "250GB",
            "duration"=> "90Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "6000",
            "code"=> "MM5",
            "allowance"=> "20GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "500",
            "code"=> "MM6",
            "allowance"=> "1GB",
            "duration"=> "7Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "1500",
            "code"=> "MM7",
            "allowance"=> "6GB",
            "duration"=> "7Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "2500",
            "code"=> "MM8",
            "allowance"=> "6GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "3000",
            "code"=> "MM9",
            "allowance"=> "8GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "20000",
            "code"=> "MB1",
            "allowance"=> "110GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "13500",
            "code"=> "MB2",
            "allowance"=> "30GB",
            "duration"=> "SME Data Share Bundle"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "40000",
            "code"=> "MB3",
            "allowance"=> "90GB",
            "duration"=> "SME Data Share Bundle"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "65000",
            "code"=> "MB4",
            "allowance"=> "150GB",
            "duration"=> "SME Data Share Bundle"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "2000",
            "code"=> "MB5",
            "allowance"=> "4.5GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "3500",
            "code"=> "MD4",
            "allowance"=> "10GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "5000",
            "code"=> "MD1",
            "allowance"=> "15GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "10000",
            "code"=> "MD2",
            "allowance"=> "40GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $mtn->id,
            "amount"=> "15000",
            "code"=> "MD3",
            "allowance"=> "75GB",
            "duration"=> "30Days"
        ]);


        // AIRTEL BUNDLES
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "50",
            "code"=> "AI1",
            "allowance"=> "40MB",
            "duration"=> "1Day"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "100",
            "code"=> "AI2",
            "allowance"=> "100MB",
            "duration"=> "1Day"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "200",
            "code"=> "AI3",
            "allowance"=> "200MB",
            "duration"=> "3Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "300",
            "code"=> "AI4",
            "allowance"=> "350MB",
            "duration"=> "7Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "300",
            "code"=> "A20",
            "allowance"=> "1GB",
            "duration"=> "1Day"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "500",
            "code"=> "AI5",
            "allowance"=> "750MB",
            "duration"=> "14Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "500",
            "code"=> "A21",
            "allowance"=> "2GB",
            "duration"=> "1Day"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "1000",
            "code"=> "AI6",
            "allowance"=> "1.5GB",
            "duration"=> "30Day"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "50",
            "code"=> "AI1",
            "allowance"=> "40MB",
            "duration"=> "1Day",
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "1200",
            "code"=> "A22",
            "allowance"=> "2GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "1500",
            "code"=> "AI7",
            "allowance"=> "3GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "1500",
            "code"=> "A23",
            "allowance"=> "6GB",
            "duration"=> "7Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "2000",
            "code"=> "AI8",
            "allowance"=> "4.5GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "2500",
            "code"=> "AI9",
            "allowance"=> "6GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "3000",
            "code"=> "AR1",
            "allowance"=> "10GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "4000",
            "code"=> "AR2",
            "allowance"=> "11GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "8000",
            "code"=> "A24",
            "allowance"=> "25GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "10000",
            "code"=> "AR4",
            "allowance"=> "40GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "15000",
            "code"=> "AR5",
            "allowance"=> "75GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "20000",
            "code"=> "AR6",
            "allowance"=> "120GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "30000",
            "code"=> "AB5",
            "allowance"=> "200GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "36000",
            "code"=> "AB1",
            "allowance"=> "280GB",
            "duration"=> "30Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "50000",
            "code"=> "AB2",
            "allowance"=> "400GB",
            "duration"=> "90Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "60000",
            "code"=> "AB3",
            "allowance"=> "500GB",
            "duration"=> "120Days"
        ]);
        \App\Model\BillerItem::create([
            'biller_id' => $airtel->id,
            "amount"=> "100000",
            "code"=> "AB4",
            "allowance"=> "1TB",
            "duration"=> "365days"
        ]);

    }
}
