<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BillerItem extends Model
{
    protected $guarded = ['id'];


    public function biller()
    {
        return $this->belongsTo(Biller::class,'biller_id');
    }
}
