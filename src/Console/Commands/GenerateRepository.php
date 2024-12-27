<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use josanangel\ServiceRepositoryManager\Services\RepositoryManager;

class GenerateRepository extends GeneratorCommand
{
    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'make:repositoryv2 {name}';

    // La descripción del comando
    protected $description = 'Generate a repository class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');

        $repositoryManager = new RepositoryManager($name);
        $repositoryManager->run();

        $this->info('Generate repository v2');
    }


}
