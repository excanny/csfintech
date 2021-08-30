<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SagePaySetting extends Model
{
    protected $guarded = ['id'];

    public function business () {
        return $this->belongsTo(Business::class);
    }
}
