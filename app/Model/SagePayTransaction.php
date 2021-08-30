<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SagePayTransaction extends Model
{
    protected $guarded = ['id'];

    // Status
    public static $PENDING = 'PENDING';
    public static $FAILED = 'FAILED';
    public static $SUCCESSFUL = 'SUCCESSFUL';

    // Authentication status
    public static $INITIATED = 'INITIATED';
    public static $IN_PROGRESS = 'IN_PROGRESS';
    public static $AUTH_SUCCESSFUL = 'SUCCESSFUL';
    public static $AUTH_FAILED = 'FAILED';


    // Payment Status
    public static $PAY_SUCCESSFUL = 'PAY_SUCCESSFUL';



    public function business () {
        return $this->belongsTo(Business::class);
    }

    public static function generatePayReference()
    {
        $reference = strtoupper(Str::random());

        if ( is_null(SagePayTransaction::where('pay_reference', $reference)->first()) ) {
            return $reference;
        }

        return self::generatePayReference();
    }
}
