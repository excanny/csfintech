<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WalletTopUpRequest extends Model
{
    protected $guarded = ['id'];

    // status
    public static $PENDING = 'PENDING';
    public static $APPROVED = 'APPROVED';
    public static $REJECTED = 'REJECTED';

    public function business () {
        return $this->belongsTo(Business::class, 'business_id');
    }

}
