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
        $viewsPath = realpath(__DIR__.'/../../resources/views');
        $configPath = realpath(__DIR__.'/../config/parameters.php');
        $migrationPath = realpath(__DIR__.'/../database/migrations');
        $routesPath = realpath(__DIR__.'/../routes.php');
        $publicPath = realpath(__DIR__.'/../../public/');

        $this->mergeConfigFrom(
            $configPath, 'parameters'
        );

        $this->loadMigrationsFrom($migrationPath);

        $this->loadViewsFrom($viewsPath , 'parameters');

        $this->loadRoutesFrom($routesPath);

        $this->publishes(
            [
                $publicPath => public_path('vendor/parameters'),
            ], 'public');

        $this->publishes(
            [
                $configPath => config_path('parameters.php'),
            ], 'config');

        $this->publishes(
            [
                $viewsPath => resource_path('views/vendor/parameters'),
            ], 'views');

        $this->publishes(
            [
                $configPath => config_path('parameters.php'),
            ], 'config');

        $this->publishes(
            [
                $migrationPath => database_path('migrations'),
            ], 'migrations');

        $this->setConnection();

        Parameter::observe(ParameterObserver::class);
    }

    public function setConnection()
    {
      $connection = Config::get('parameters.default_connection');

      if ($connection !== 'parameters_default') {
        $wardrobeConfig = Config::get('database.connections.'.$connection);
      } else {
        $connection = Config::get('parameters.default_connection');
        $wardrobeConfig = Config::get('parameters.connections.'.$connection);
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
        require_once(realpath(__DIR__. '/../Helpers/parameters.php'));
        new ParametersSingleton();
    }
}
