<?php

namespace IdeoLearn\Core\Providers;


use IdeoLearn\Core\Helpers\StorageConfig;
use IdeoLearn\Core\Services\BucketService;
use IdeoLearn\Core\Services\Contracts\BucketInterface;
use IdeoLearn\Core\Services\Contracts\StorageInterface;
use IdeoLearn\Core\Services\StorageService;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(StorageConfig::class, function () {
            return new StorageConfig();
        });

        $this->app->bind(BucketInterface::class, function ($app) {
            return new BucketService($app->make(StorageConfig::class));
        });

        $this->app->singleton(StorageInterface::class, function ($app) {
            return new StorageService(
                $app->make(StorageConfig::class),
                $app->make(BucketInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
