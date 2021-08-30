<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SagePayWallet extends Model
{
    public $incrementing = false;

    public $guarded = ['id'];

    public function owner()
    {
        return $this->hasOne(Business::class, 'id', 'business_id')
            ->select(['id', 'name', 'email']);
    }

    public function transactions()
    {
        return $this->hasMany(SagePayWalletTransaction::class);
    }
}
