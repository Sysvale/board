<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class BoardList extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'position',
        'key',
        'user_story_holder'
    ];

    protected $appends = ['id'];
    protected $hidden = ['_id'];

    public function cards()
    {
        return $this->hasMany('App\Models\Card');
    }

}
