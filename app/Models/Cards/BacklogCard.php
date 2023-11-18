<?php

namespace App\Models;

use App\Models\Scopes\BacklogCardScope;

class BacklogCard extends Card
{
    protected static function booted(): void
    {
        static::addGlobalScope(new BacklogCardScope);
    }
}
