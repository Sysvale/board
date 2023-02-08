<?php

namespace App\Http\Middleware;

use App\Models\Card;
use App\Models\Member;
use App\Models\Scopes\CompanyScope;
use App\Models\Team;
use App\Models\Workspace;
use App\Models\Process;
use Closure;
use Illuminate\Support\Facades\Auth;

class EnableCompanyGlobalScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            $company_id = auth()->user()->member->company_id;

            Card::addGlobalScope(new CompanyScope($company_id));
            Member::addGlobalScope(new CompanyScope($company_id));
            Team::addGlobalScope(new CompanyScope($company_id));
            Workspace::addGlobalScope(new CompanyScope($company_id));
            Process::addGlobalScope(new CompanyScope($company_id));
        }
        
        return $next($request);
    }
}
