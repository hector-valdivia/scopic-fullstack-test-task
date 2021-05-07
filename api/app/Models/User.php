<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'max_amount_bid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function activeBids(){
        return Item::where('last_bid_user_id', $this->id)
            ->where('ends_at', '>', Carbon::now())
            ->sum('last_bid');
    }

    public function budget(){
        return $this->max_amount_bid - $this->activeBids();
    }

    public function items(){
        return $this->hasMany(Item::class, 'user_id', 'id');
    }

    public function autoBids(){
        return $this->hasMany(ItemAutoBid::class, 'user_id', 'id');
    }
}
