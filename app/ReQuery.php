<?php

namespace App;

use App\Model\Transaction;
use Illuminate\Database\Eloquent\Model;

class ReQuery extends Model
{
    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
