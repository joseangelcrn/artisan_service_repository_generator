<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Support\Facades\Artisan;

class GenerateRepository extends GeneratorCommand
{
    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'generate:repository {name}';

    // La descripción del comando
    protected $description = 'Generate a repository class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Lógica del comando
        $this->info('¡Comando ejecutado con éxito!');
        $name = $this->normalizeClassName(
            $this->argument('name')
        );

        $repositoryClassName = $name."Repository";
        Artisan::call('make:class Repositories/'.$repositoryClassName);
    }


}
