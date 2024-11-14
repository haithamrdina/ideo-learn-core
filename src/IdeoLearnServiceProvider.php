<?php

namespace IdeoLearn\Core;

use IdeoLearn\Core\Providers\StorageServiceProvider;
use Illuminate\Support\ServiceProvider;

class IdeoLearnServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(StorageServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
