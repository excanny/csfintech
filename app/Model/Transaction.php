<?php

namespace App\Model;

use App\ReQuery;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $guarded = [];

    // public function wallet()
    // {
    //     return $this->belongsTo(Wallet::class);
    // }

    public function business(){

        return $this->belongsTo(Business::class);
    }

    public function requery()
    {
        return $this->hasOne(ReQuery::class);
    }
}
