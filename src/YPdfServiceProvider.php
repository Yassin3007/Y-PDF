<?php

namespace YPdf;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class YPdfServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ypdf.php', 'ypdf');

        $this->app->singleton(YPdfManager::class, function ($app) {
            return new YPdfManager(
                $app['config'],
                $app['view'],
                new Filesystem()
            );
        });

        $this->app->alias(YPdfManager::class, 'ypdf');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/ypdf.php' => config_path('ypdf.php'),
        ], 'ypdf-config');
    }
}
