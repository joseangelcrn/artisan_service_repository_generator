<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;


use josanangel\ServiceRepositoryManager\Console\Commands\Traits\AuxGenerator;
use josanangel\ServiceRepositoryManager\Services\RepositoryManager;
use josanangel\ServiceRepositoryManager\Services\ServiceManager;

class GenerateService extends GeneratorCommand
{

    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'make:service {name} {--repositories=} {--services=} {--module=}';

    // La descripción del comando
    protected $description = 'Generate a service class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->option('module');

        $repositories = $this->option('repositories',[]);
        $repositories = explode(',',$repositories);
        $repositories = collect($repositories);
        $repositories = $repositories->filter();


        $services = $this->option('services',[]);
        $services = explode(',',$services);
        $services = collect($services);
        $services = $services->filter();

        $serviceManager = new ServiceManager($name,$module);

        foreach ($repositories as $repository){
            $repositoryManager = new RepositoryManager($repository,$module);
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
            $auxServiceManager = new ServiceManager($service,$module);
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

        $this->info('Service has been created successfully.');
    }
}
