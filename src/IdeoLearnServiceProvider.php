<?php

namespace IdeoLearn\Core;

use IdeoLearn\Core\Providers\StorageServiceProvider;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider;
use Spatie\Translatable\TranslatableServiceProvider;

class IdeoLearnServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(StorageServiceProvider::class);
        $this->app->register(LaravelLocalizationServiceProvider::class);
        $this->app->register(TranslatableServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish mcamara/laravel-localization configuration
        $this->publishes([
            base_path('vendor/mcamara/laravel-localization/config/laravellocalization.php') => config_path('laravellocalization.php'),
        ], 'ideolean-localization');

        // Publish spatie/laravel-translatable configuration (if needed)
        $this->publishes([
            base_path('vendor/spatie/laravel-translatable/config/translatable.php') => config_path('translatable.php'),
        ], 'ideolean-translatable');
    }
}