<?php

namespace josanangel\ServiceRepositoryManager\src;

use Illuminate\Console\Command;

class GenerateRepository extends Command
{
    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'generate:repository';

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
    }
}
