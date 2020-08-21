<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;


class Card extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'board_list_id',
        'team_id',
        'user_story_id',
        'title',
        'link',
        'position',
    ];
    protected $appends = ['id'];
    protected $hidden = ['_id'];

    public function boardList()
    {
        return $this->belongsTo('App\Models\BoardList');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function userStory()
    {
        return $this->belongsTo('App\Models\Card');
    }
}
