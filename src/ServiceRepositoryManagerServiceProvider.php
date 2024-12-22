<?php

namespace josanangel\ServiceRepositoryManager;

use Illuminate\Support\ServiceProvider;
use josanangel\ServiceRepositoryManager\Console\Commands\GenerateRepository;
use josanangel\ServiceRepositoryManager\Console\Commands\GenerateService;

class ServiceRepositoryManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Registra vistas, migraciones, rutas, etc.
        $this->commands([
            GenerateRepository::class,
            GenerateService::class,
        ]);
    }

    public function register()
    {
        // Registra bindings en el contenedor de servicios
    }
}
