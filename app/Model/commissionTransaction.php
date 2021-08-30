<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class commissionTransaction extends Model
{
    protected $guarded = ['id'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function business () {
        return $this->belongsTo(Business::class);
    }


}
