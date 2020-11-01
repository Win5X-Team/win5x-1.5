<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'global_promocodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'code', 'usages', 'sum', 'type', 'time', 'tick'];
}
