<?php


namespace App\Classes;


use App\Model\Biller;

class Runners
{


    public static function updateBillers()
    {
        // internet data packages
        //$packages = self::getPackages("VDA", '08163240721', 'MTN');


        $service = 'AIRTELDATA';

        $res = Sonite::dataLookup($service);

        dd($res);

        if ( $res['success'] && $res['data']->status ) {
            $data = $res['data']->data;

            $biller = Biller::create([
                'biller_type' => 'DATA',
                'provider'  => 'SONITE',
                'label1'    => 'Phone Number',
                'info'      => 'Data subscription',
                'short_name'    => $service,
                'type'    => $service,
            ]);

            foreach ($data as $item) {
                $biller->items()->create([
                    'title' => $item->description,
                    'amount' => $item->amount,
                    'code' => $item->code,
                    'duration' => $item->duration,
                ]);
            }
        }
    }

}
