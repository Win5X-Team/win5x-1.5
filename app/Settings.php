<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['chance','yt_chance', 'promo_sum', 'promo_percent', 'min_with',
        'ap_id', 'ap_secret', 'payment_disabled', 'ap_api_key', 'ap_api_id',
        'max_bet_increase', 'min_in', 'vk_service',
        'warn_enabled', 'warn_title', 'warn_text', 'temp_promo_sum',
        'crash_s', 'crash_m', 'crash_b', 'crash_h', 'crash_u'
    ];
}
