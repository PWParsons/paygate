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
    }
}
