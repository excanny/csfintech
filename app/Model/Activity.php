<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = ['id'];

    public function user () {
        return $this->belongsTo(User::class, 'user_id');
    }
}
