<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Notification extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'read_status', 'icon', 'title', 'message', 'type', 'time'];

    public static function send($user_id, $icon, $title, $message, $type = 'default') {
        return ['id' => Notification::insertGetId([
            'read_status' => 0,
            'time' => time(),
            'icon' => $icon,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'user_id' => $user_id
        ])];
    }

}
