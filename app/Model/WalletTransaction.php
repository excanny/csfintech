<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{

    protected $table = 'wallet_transactions';

    protected $guarded = ['id'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
