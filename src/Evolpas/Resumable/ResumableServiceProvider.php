<?php

namespace Evolpas\Resumable;

use Illuminate\Support\ServiceProvider;

class ResumableServiceProvider extends ServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->package('evolpas/resumable');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app['resumable'] = $this->app->share(function($app) {
            return new Resumable();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('resumable');
    }

}
