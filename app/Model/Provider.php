<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $guarded = ['id'];

    public static $ACTIVE = 'ACTIVE';
    public static $INACTIVE = 'INACTIVE';

//    public static function current () {
//        return Provider::where('status', Provider::$ACTIVE)->first();
//    }

    public static function airtime_current () {
        return Provider::where('status', Provider::$ACTIVE)->where('product','AIRTIME')->first();
    }

    public static function data_current () {
        return Provider::where('status', Provider::$ACTIVE)->where('product','DATA')->first();
    }

    public static function electricity_current () {
        return Provider::where('status', Provider::$ACTIVE)->where('product','ELECTRICITY')->first();
    }

    public static function cableTv_current () {
        return Provider::where('status', Provider::$ACTIVE)->where('product','CABLETV')->first();
    }
}
