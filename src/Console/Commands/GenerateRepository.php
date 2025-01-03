<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use josanangel\ServiceRepositoryManager\Services\RepositoryManager;

class GenerateRepository extends GeneratorCommand
{
    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'make:repository {name} {--module=}';

    // La descripción del comando
    protected $description = 'Generate a repository class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $name = $this->argument('name');
        $module = $this->option('module');

        $repositoryManager = new RepositoryManager($name,$module);

        $repositoryManager->run();

        $this->info('');
    }


}
