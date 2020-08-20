<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;


class Card extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'board_list_id',
        'title',
        'link',
    ];
    protected $appends = ['id'];
    protected $hidden = ['_id'];

    public function boardList()
    {
        return $this->belongsTo('App\Models\BoardList');
    }
}
