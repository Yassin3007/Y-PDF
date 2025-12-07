<?php

namespace Imar\HeadlessPdf;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class HeadlessPdfServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/headless-pdf.php', 'headless-pdf');

        $this->app->singleton(HeadlessPdfManager::class, function ($app) {
            return new HeadlessPdfManager(
                $app['config'],
                $app['view'],
                new Filesystem()
            );
        });

        $this->app->alias(HeadlessPdfManager::class, 'headlesspdf');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/headless-pdf.php' => config_path('headless-pdf.php'),
        ], 'headless-pdf-config');
    }
}
