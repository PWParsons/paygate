<?php

namespace PWParsons\PayGate;

use Illuminate\Support\ServiceProvider;

class PayGateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/paygate.php' => config_path('paygate.php'),
            ], 'config');
        }

        $this->loadViewsFrom(__DIR__.'/resources/views', 'paygate');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/paygate.php', 'paygate');

        $this->app->singleton('paygate', function ($app) {
            return new PayGate($app->config->get('paygate'));
        });
    }

    public function provides(): array
    {
        return ['paygate'];
    }
}
