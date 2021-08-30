<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CapricornDataPlan extends Model
{
    protected $guarded = ['id'];

    public static $mtn = 'mtn';
    public static $airtel = 'airtel';
    public static $glo = 'glo';
    public static $etisalat = 'etisalat';
}
