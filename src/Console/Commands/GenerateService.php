<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;


use josanangel\ServiceRepositoryManager\Console\Commands\Traits\AuxGenerator;
use josanangel\ServiceRepositoryManager\Services\RepositoryManager;
use josanangel\ServiceRepositoryManager\Services\ServiceManager;

class GenerateService extends GeneratorCommand
{
    use AuxGenerator;

    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'make:servicev2 {name} {--repositories=} {--services=}';

    // La descripción del comando
    protected $description = 'Generate a service class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');

        $repositories = $this->option('repositories',[]);
        $repositories = explode(',',$repositories);
        $repositories = collect($repositories);
        $repositories = $repositories->filter();


        $services = $this->option('services',[]);
        $services = explode(',',$services);
        $services = collect($services);
        $services = $services->filter();

        $serviceManager = new ServiceManager($name);

        foreach ($repositories as $repository){
            $repositoryManager = new RepositoryManager($repository);
            $repositoryManager->run();

            $serviceManager->addAttributeToClass(
                $repositoryManager->getVariable(),
                $repositoryManager->getType(),
                $repositoryManager->getNameSpace()
            );

            $serviceManager->addParamToConstructor(
                $repositoryManager->getVariable(),
                $repositoryManager->getType(),
                $repositoryManager->getNameSpace()

            );
        }

        foreach ($services as $service){
            $auxServiceManager = new ServiceManager($service);
            $auxServiceManager->run();

            $serviceManager->addAttributeToClass(
                $auxServiceManager->getVariable(),
                $auxServiceManager->getType(),
                $auxServiceManager->getNameSpace()
            );

            $serviceManager->addParamToConstructor(
                $auxServiceManager->getVariable(),
                $auxServiceManager->getType(),
                $auxServiceManager->getNameSpace()
            );
        }

        $serviceManager->run();

        $this->info('Generate service v2');
    }
}
