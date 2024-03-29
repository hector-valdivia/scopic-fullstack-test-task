<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'ends_at',
        'last_bid',
        'last_bid_user_id',
    ];

    protected $dates = [
        'ends_at',
        'created_at',
        'updated_at'
    ];

    public function owner(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lastBidUser(){
        return $this->belongsTo(User::class, 'last_bided_user_id', 'id');
    }
}
