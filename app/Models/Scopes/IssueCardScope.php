<?php

namespace App\Models\Scopes;

use App\Enums\CardTypesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;

class IssueCardScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('type', CardTypesEnum::ISSUE_CARD);
    }
}
