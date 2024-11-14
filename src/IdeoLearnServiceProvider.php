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
        if ($this->app->runningInConsole()) {
            // Publish mcamara/laravel-localization configuration
            $this->publishes([
                base_path('vendor/mcamara/laravel-localization/config/laravellocalization.php') => config_path('laravellocalization.php'),
            ], 'ideolean-localization');

            // Register the command if we're using the application via CLI
            $this->commands([
                Console\Commands\PublishCommand::class
            ]);
        }
    }
}