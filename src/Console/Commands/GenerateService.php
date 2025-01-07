<?php

/**
 * Implementation of GeneratorCommand abstract class, dedicated to generate Service files.
 * This file is handled by ServiceManager, this one contains complex and configurable logic through service file creation
 *
 * Required Params:
 *  + {name} : Name of service you are going to create.
 *
 * Optional Params:
 *  + {--module} : Indicate where is going to generate file(s)
 *  + {--repositories} : Indicate possible repositories which might to be declared as injected dependencies in main service file
 *  + {--services} : Indicate possible services which might to be declared as injected dependencies in main service file
 *
 */

namespace josanangel\ServiceRepositoryManager\Console\Commands;


use josanangel\ServiceRepositoryManager\Console\Commands\Traits\AuxGenerator;
use josanangel\ServiceRepositoryManager\Services\RepositoryManager;
use josanangel\ServiceRepositoryManager\Services\ServiceManager;

class GenerateService extends GeneratorCommand
{

    protected $signature = 'make:service {name} {--repositories=} {--services=} {--module=}';

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
