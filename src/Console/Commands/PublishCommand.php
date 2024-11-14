<?php

namespace IdeoLearn\Core\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'ideolearn:publish';

    protected $description = 'Publish all IdeoLearn required configurations';

    public function handle()
    {
        $this->info('Publishing mcamara/laravel-localization configuration...');
        $this->call('vendor:publish', [
            '--provider' => 'Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider',
            '--force' => true
        ]);
    }
}
