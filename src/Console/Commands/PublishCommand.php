<?php

namespace IdeoLearn\Core\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'ideolean:publish';

    protected $description = 'Publish all IdeoLearn required configurations';

    public function handle()
    {
        $this->info('Publishing mcamara/laravel-localization configuration...');
        $this->call('vendor:publish', [
            '--provider' => 'Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider',
            '--force' => true
        ]);

        $this->info('Publishing spatie/laravel-translatable configuration...');
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Translatable\TranslatableServiceProvider',
            '--force' => true
        ]);

        $this->info('All configurations have been published successfully!');
    }
}