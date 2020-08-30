<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;


class Team extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'key',
        'extended_task_flow',
    ];

    protected $appends = ['id'];
    protected $hidden = ['_id'];

    public function cards()
    {
        return $this->hasMany('App\Models\Card');
    }

}
