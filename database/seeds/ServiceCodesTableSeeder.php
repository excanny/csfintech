<?php

use Illuminate\Database\Seeder;

class ServiceCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = [
            [
                'provider' => 'SHAGO',
                'code' => 'AOV',
                'alias' => 'disco-validation',
                'description' => 'Disco Validation',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'AOB',
                'alias' => 'disco-purchase',
                'description' => 'Disco Purchase',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'VDA',
                'alias' => 'data-lookup',
                'description' => 'Data Lookup',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'BDA',
                'alias' => 'data-purchase',
                'description' => 'Data Purchase',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'QAB',
                'alias' => 'airtime-pruchase',
                'description' => 'Airtime Purchase',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'GDS',
                'alias' => 'cable-tv-validation',
                'description' => 'Cable TV Validation',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'GDB',
                'alias' => 'cable-tv-purchase',
                'description' => 'Cable TV Purchase',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'SPV',
                'alias' => 'spectranet-looup',
                'description' => 'Spectranet Data Lookup',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'SPB',
                'alias' => 'spectranet-purchase',
                'description' => 'Spectranet Purchase',
            ],
            [
                'provider' => 'SHAGO',
                'code' => 'SMV',
                'alias' => 'smile-validation',
                'description' => 'Smile Bundle Validation',
            ],
            [
                'provider' => 'SONITE',
                'code' => 'MTNDATA',
                'alias' => 'sonite-mtn-data',
                'description' => 'MTN Data subscription',
                'type' => 'MTN'
            ],
            [
                'provider' => 'SONITE',
                'code' => 'AIRTELDATA',
                'alias' => 'sonite-airtel-data',
                'description' => 'MTN Data subscription',
                'type' => 'AIRTEL'
            ],
            [
                'provider' => 'SONITE',
                'code' => 'GLODATA',
                'alias' => 'sonite-glo-data',
                'description' => 'Glo Data subscription',
                'type' => 'GLO'
            ],
            [
                'provider' => 'SONITE',
                'code' => '9MOBILEDATA',
                'alias' => 'sonite-9mobile-data',
                'description' => '9Mobile Data subscription',
                'type' => '9MOBILE'
            ]
        ];


        foreach ($codes as $code) {
            \App\Model\ServiceCode::create($code);
        }
    }
}
