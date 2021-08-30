<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    // Charge types
    public static $PERCENTAGE = 'PERCENTAGE';
    public static $FIXED = 'FIXED';

    // Slugs
    public static $airtime = 'airtime';
    public static $data = 'data';
    public static $cable_tv = 'cable_tv';
    public static $electricity = 'electricity';
    public static $transfer = 'transfer';
    public static $payment_gateway = 'payment_gateway';

    // Transfer bands
    public static $transfer_band_1 = '1 - 5000';
    public static $transfer_band_2 = '5000 - 50000';
    public static $transfer_band_3 = '50000 and above';


    // AIRTIME COMMISSIONS
    public static $AIRTIME = [
        'MTN' => 0,
        'AIRTEL' => 0,
        'GLO' => 0,
        '9MOBILE' => 0
    ];


    // DATA COMMISSIONS
    public static $DATA = [
        'MTN' => 0,
        'AIRTEL' => 0,
        'GLO' => 0,
        '9MOBILE' => 0,
        'Smile' => 0,
        'Spectranet' => 0
    ];


    // CABLE TV COMMISSIONS
    public static $CABLE_TV = [
        'DSTV' => 0,
        'GOTV' => 0,
        'Startimes' => 0,
    ];


    // ELECTRICITY COMMISSIONS
    public static $ELECTRICITY = [
        'Eko Electricity (EKEDC)' => 0,
        'Ikeja Electricity (IKEDC)' => 0,
        'Port Harcourt Electricity (PHEDC)' => 0,
        'Ibadan Disco (IBEDC)' => 0,
        'Abuja Electricity (AEDC)' => 0,
        'Jos Electricity (JEDC)' => 0,
        'Kaduna Electricity (KAEDC)' => 0,
        'Kano Electricity (KEDC)' => 0,
        'Enugu Electricity Prepaid (EEDC)' => 0
    ];

    // TRANSFER COMMISSIONS
    public static $TRANSFER = [
        '1 - 5000' => 0,
        '5000 - 50000' => 0,
        '50000 and above' => 0
    ];


}
