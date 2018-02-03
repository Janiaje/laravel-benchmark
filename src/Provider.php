<?php

namespace Janiaje\Benchmark;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->provideConfig();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $parameters = [
            Benchmark::class,
            function () {
                return new Benchmark;
            }
        ];

        $singleton = config('benchmark.singleton');
        if ($singleton === true || $singleton === null) {
            $this->app->singleton(...$parameters);
        } else {
            $this->app->bind(...$parameters);
        }
    }

    /**
     * Provide configuration
     */
    private function provideConfig()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('benchmark.php'),
        ], 'config');

        if (!file_exists(config_path('benchmark.php'))) {
            $this->mergeConfigFrom(__DIR__ . '/config.php', 'benchmark');
        }
    }
}
