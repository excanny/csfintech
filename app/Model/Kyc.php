<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $table = 'kyc';

    // Kyc Types
    public static $bvn = 'bvn';
    public static $ibvn = 'ibvn';
    public static $nin = 'nin';
    public static $pvc = 'pvc';

    public static function getKycCost ( $slug ) {
        return Kyc::orderBy('id')->where( 'slug', $slug )->first()->cost;
    }
}
