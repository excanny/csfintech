<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
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
        return $this->hasMany(WalletTransaction::class);
    }

    public function commissionTransactions()
    {
        return $this->hasMany(commissionTransaction::class);
    }
}
