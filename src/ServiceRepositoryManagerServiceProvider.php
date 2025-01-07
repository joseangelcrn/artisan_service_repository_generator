<?php

namespace josanangel\ServiceRepositoryManager;

use Illuminate\Support\ServiceProvider;
use josanangel\ServiceRepositoryManager\Console\Commands\GenerateRepository;
use josanangel\ServiceRepositoryManager\Console\Commands\GenerateService;

class ServiceRepositoryManagerServiceProvider extends ServiceProvider
{

    const CONFIG_TAG='service_repository_manager_config';

    public function boot()
    {
        // Register the commands included in this package for use in Artisan
        // These commands are responsible for generating repository and service classes
        $this->commands([
            GenerateRepository::class,
            GenerateService::class
        ]);

        $this->publishes([
            __DIR__.'/../config/global.php' => config_path('service_repository_manager.php')
        ],self::CONFIG_TAG);
    }

    public function register()
    {
        // Bindings to service container
        $this->mergeConfigFrom(
            __DIR__ . '/../config/global.php', self::CONFIG_TAG
        );
    }
}
