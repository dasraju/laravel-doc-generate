<?php

namespace Raju\DocGenerator;

use Illuminate\Support\ServiceProvider;
use Raju\DocGenerator\Commands\GenerateDocs;

class DocGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register package resources here
    }

    public function boot()
    {
        // Register the command
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateDocs::class,
            ]);
        }
    }
}
