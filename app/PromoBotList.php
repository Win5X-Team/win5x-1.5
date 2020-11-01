<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class PromoBotList extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'promo_bot_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'username', 'avatar', 'vk_public', 'vk_id', 'tutorial'];
}
