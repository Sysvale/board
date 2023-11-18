<?php

namespace App\Models;

use App\Models\Scopes\TaskCardScope;

class TaskCard extends Card
{
    protected static function booted(): void
    {
        static::addGlobalScope(new TaskCardScope);
    }
}
