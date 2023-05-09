<?php

namespace App\Http\Middleware;

use App\Models\CompanyClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser;
use Sysvale\Mongodb\Passport\Token;

class ResolveCompanyTenant
{
	private $parser;
	private $tokenRepository;

	public function __construct(Parser $parser, TokenRepository $tokenRepository)
	{
		$this->parser = $parser;
		$this->tokenRepository = $tokenRepository;
	}

	public function handle(Request $request, Closure $next)
	{
		if(Auth::check()) {
			$company_id = auth()->user()->member->company_id;
		}

		if (!empty($request->bearerToken())) {
			$bearerToken = request()->bearerToken();
			$tokenId = $this->parser->parse($bearerToken)->claims()->get('jti');
			$token = $this->tokenRepository->find($tokenId);
			$company_id = CompanyClient::where('client_id', $token->client_id)->first()->company_id;
		}

		$request->merge(['company_id' => $company_id ?? null]);

		return $next($request);
	}
}
