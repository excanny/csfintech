<?php

namespace App\Model;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class DisputeMessage extends Model
{
    protected $guarded = ['id'];

    // Message content types
    public static $text = 1;
    public static $image = 2;

    public function dispute () {
        return $this->belongsTo(Dispute::class, 'dispute_id');
    }

    public function admin () {
        return $this->belongsTo( User::class, 'admin_id');
    }
}
