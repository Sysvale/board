<?php

namespace App\Providers;

use App\Models\Card;
use App\Models\Team;
use App\Observers\CardObserver;
use App\Observers\TeamObserver;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register(): void
	{
		$this->app->singleton(FakerGenerator::class, function () {
			return FakerFactory::create('pt_BR');
		});
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		JsonResource::withoutWrapping();
		Card::observe(CardObserver::class);
		Team::observe(TeamObserver::class);

        Passport::tokensCan([
            'teste1' => 'Place orders',
            'teste2' => 'Check order status',
        ]);
	}
}
