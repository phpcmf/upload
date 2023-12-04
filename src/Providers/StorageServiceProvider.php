<?php

namespace Cmf\Upload\Providers;

use Cmf\Upload\Adapters\Manager;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Manager::class);
    }
}
