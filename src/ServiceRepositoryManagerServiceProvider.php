<?php

namespace josanangel\ServiceRepositoryManager;

use Illuminate\Support\ServiceProvider;
use josanangel\ServiceRepositoryManager\src\GenerateRepository;

class ServiceRepositoryManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Registra vistas, migraciones, rutas, etc.
        $this->commands([
            GenerateRepository::class
        ]);
    }

    public function register()
    {
        // Registra bindings en el contenedor de servicios
    }
}
