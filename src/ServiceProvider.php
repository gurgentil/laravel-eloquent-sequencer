<?php

namespace Gurgentil\LaravelEloquentSequencer;

use Gurgentil\LaravelEloquentSequencer\Console\Commands\FlushSequenceValues;
use Gurgentil\LaravelEloquentSequencer\Console\Commands\PopulateSequenceValues;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('eloquentsequencer.php'),
            ], 'config');

            $this->commands([
                PopulateSequenceValues::class,
                FlushSequenceValues::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'eloquentsequencer');
    }
}
