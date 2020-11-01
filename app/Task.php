<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Task extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    public function getDateFormat(){
        return 'Y-m-d H:i:s.u';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['start_time', 'end_time', 'game_id', 'value', 'reward', 'price'];

}
