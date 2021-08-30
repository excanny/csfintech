<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SagePayWalletTransaction extends Model
{
    protected $table = 'sage_pay_wallet_transactions';

    protected $guarded = ['id'];

    public function wallet()
    {
        return $this->belongsTo(SagePayWallet::class);
    }
}
