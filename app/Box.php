<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Box extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'contains', 'front', 'top', 'bottom', 'side', 'lid_front', 'lid_top', 'lid_bottom', 'lid_side', 'price', 'is_free'];

    public static function getName($value, $id) {
        $declOfNum = function($num, $titles) {
            $cases = array(2, 0, 1, 1, 1, 2);
            return $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
        };

        $level = Auth::guest() ? 1 : Auth::user()->level;

        return [
            1 => $value." руб.",
            2 => $level < 10 ? $value."% ".$declOfNum($value, array('опыт', 'опыта', 'опыта')) : $value / 20 . ' руб.'
        ][$id];
    }

    public static function isFreeAvailable() {
        if(Auth::guest()) return false;
        if(Auth::user()->free_case_time + 86400 > time()) return false;
        return true;
    }

}
