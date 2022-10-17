<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;

class CompanyScope implements Scope
{
    private ?string $company_id;

    public function __construct(?string $company_id)
    {
        $this->company_id = $company_id;
    }

    public function apply(Builder $builder, Model $model): void
    {
        dump($this->company_id);
        if ($this->company_id) {
            $builder->where('company_id', $this->company_id);
        }
    }
}
