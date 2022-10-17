<?php

namespace App\Traits\Models;

use App\Models\Scopes\CompanyScope;

trait hasCompanyGlobalScope 
{
    protected static function boot()
    {
        parent::boot();
        
        $company_id = auth()->user()->member->company_id ?? null;

        static::addGlobalScope(new CompanyScope($company_id));
    }
}