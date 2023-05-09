<?php

namespace App\Http\Middleware;

use App\Models\Card;
use App\Models\Member;
use App\Models\Scopes\CompanyScope;
use App\Models\Team;
use App\Models\Workspace;
use App\Models\Process;
use Closure;

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
		if(!empty($request->company_id)) {
			$company_scope = new CompanyScope($request->company_id);
			Card::addGlobalScope($company_scope);
			Member::addGlobalScope($company_scope);
			Team::addGlobalScope($company_scope);
			Workspace::addGlobalScope($company_scope);
			Process::addGlobalScope($company_scope);
		}

		return $next($request);
	}
}
