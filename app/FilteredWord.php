<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class FilteredWord extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filtered_words';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['word'];
}
