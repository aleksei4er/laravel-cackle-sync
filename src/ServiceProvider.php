<?php

namespace Aleksei4er\LaravelCackleSync;

use Aleksei4er\LaravelCackleSync\Console\Commands\CackleSync;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/laravel-cackle-sync.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('laravel-cackle-sync.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CackleSync::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'laravel-cackle-sync'
        );

        $this->app->bind('laravel-cackle-sync', function () {
            return new LaravelCackleSync(config('laravel-cackle-sync'));
        });
    }
}
