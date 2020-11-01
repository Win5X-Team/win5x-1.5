<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Game extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['bet','user_id', 'type', 'cell_1', 'cell_2', 'cell_3','cell_4', 'status', 'multiplier', 'server_seed', 'time', 'demo', 'history'];

    public static function toggleDisabled($game) {
        $json = json_decode(file_get_contents(storage_path().'/disabled_games.json'), true);
        if(!in_array($game, $json['disabled'])) array_push($json['disabled'], $game);
        else unset($json['disabled'][array_search($game, $json['disabled'])]);
        file_put_contents(storage_path().'/disabled_games.json', json_encode($json));
        return true;
    }

    public static function isDisabled($game) {
        $contents = file_get_contents(storage_path().'/disabled_games.json');
        return stripos($contents, $game) !== false;
    }

}
