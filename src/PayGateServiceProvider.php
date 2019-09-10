<?php

namespace PWParsons\PayGate;

use Illuminate\Support\ServiceProvider;

class PayGateServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'PayGate');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/paygate.php', 'paygate');

        // Register the service the package provides.
        $this->app->singleton('paygate', function ($app) {
            return new PayGate($app->config->get('paygate'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['paygate'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/paygate.php' => config_path('paygate.php'),
        ], 'paygate.config');
    }
}
