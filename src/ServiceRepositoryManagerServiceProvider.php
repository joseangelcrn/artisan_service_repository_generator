<?php

namespace josanangel\ServiceRepositoryManager;

use Illuminate\Support\ServiceProvider;
use josanangel\ServiceRepositoryManager\Console\Commands\GenerateRepository;
use josanangel\ServiceRepositoryManager\Console\Commands\GenerateRepositoryV2;
use josanangel\ServiceRepositoryManager\Console\Commands\GenerateService;
use josanangel\ServiceRepositoryManager\Console\Commands\GenerateServiceV2;

class ServiceRepositoryManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Registra vistas, migraciones, rutas, etc.
        $this->commands([
            GenerateRepository::class,
            GenerateService::class,

            GenerateRepositoryV2::class,
            GenerateServiceV2::class
        ]);
    }

    public function register()
    {
        // Registra bindings en el contenedor de servicios
    }
}
