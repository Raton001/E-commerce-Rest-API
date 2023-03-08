<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'membership_id', 'user_id', 'subscription_date_start'
    ];
}
