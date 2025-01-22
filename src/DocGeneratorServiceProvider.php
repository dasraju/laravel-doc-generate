<?php

namespace Rajudev\DocGenerator;

use Illuminate\Support\ServiceProvider;
use Rajudev\DocGenerator\Commands\GenerateDocs;
use Symfony\Component\Console\Application;

class DocGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Rajudev\DocGenerator\DocGeneratorServiceProvider::class, function (Application $app) {
            return new DocGeneratorServiceProvider();
        });
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
