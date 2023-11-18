<?php

namespace App\Models;

use App\Models\Scopes\IssueCardScope;

class IssueCard extends Card
{
    protected static function booted(): void
    {
        static::addGlobalScope(new IssueCardScope);
    }
}
