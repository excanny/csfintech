<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $guarded = ['id'];


    // Dispute status
    public static $open = 0;
    public static $closed = 1;

    public function messages () {
        return $this->hasMany(DisputeMessage::class, 'dispute_id');
    }

    public function business () {
        return $this->belongsTo(Business::class, 'business_id')
            ->select(['name', 'email', 'id', 'phone']);
    }

    public function admin () {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
