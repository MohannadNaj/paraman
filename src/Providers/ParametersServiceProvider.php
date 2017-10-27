<?php

namespace Parameter\Providers;

use Illuminate\Support\ServiceProvider;
use Parameter\ParametersSingleton;
use Parameter\Parameter;
use Parameter\ParameterObserver;

class ParametersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));
        $this->loadViewsFrom(realpath(__DIR__.'/../../resources/views'), 'parameters');
        $this->loadRoutesFrom(realpath(__DIR__.'/../routes.php'));
        $this->publishes(
            [
                realpath(__DIR__.'/../../public/') => public_path('vendor/parameters'),
            ], 'public');

        Parameter::observe(ParameterObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once(realpath(__DIR__. '/../Helpers/parameters.php'));
        new ParametersSingleton();

    }
}
