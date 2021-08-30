<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'pageFlowInfo' => 'array'
    ];

    public function items()
    {
        return $this->hasMany(BillerItem::class);
    }
}
