<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use Auth;

class SportBet extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sport';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['wager', 'game_id', 'user_id', 'game', 'category', 'category_index', 'status', 'multiplier', 'description_header', 'description_title', 'description_subtitle'];

}
