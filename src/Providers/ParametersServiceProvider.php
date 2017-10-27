<?php

namespace Parameter\Providers;

use Config;
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
        $this->setConnection();
        Parameter::observe(ParameterObserver::class);
    }

    public function setConnection()
    {
      $connection = Config::get('parameters.default');

      if ($connection !== 'default') {
        $wardrobeConfig = Config::get('parameters.connections.'.$connection);
      } else {
        $connection = Config::get('database.default');
        $wardrobeConfig = Config::get('database.connections.'.$connection);
      }

      Config::set('database.connections.parameters', $wardrobeConfig);
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/database.php', 'parameters'
        );
        require_once(realpath(__DIR__. '/../Helpers/parameters.php'));
        new ParametersSingleton();

    }
}
