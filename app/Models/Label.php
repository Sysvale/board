<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;


class Label extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'key',
        'color',
        'text_color',
    ];

    protected $appends = ['id'];
    protected $hidden = ['_id'];
}
