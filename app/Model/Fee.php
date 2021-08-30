<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'products' => 'array',
    ];

    public function business () {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
