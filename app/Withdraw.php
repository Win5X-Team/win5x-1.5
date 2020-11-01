<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'withdraw';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'system', 'wallet', 'amount', 'status'];
}
