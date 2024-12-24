<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;


use josanangel\ServiceRepositoryManager\Console\Commands\Traits\AuxGenerator;
use josanangel\ServiceRepositoryManager\Services\ServiceManager;

class GenerateServiceV2 extends GeneratorCommand
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
        $serviceManager->addRepositoriesAsDependencies($repositories);
        $serviceManager->addServicesAsDependencies($services);
        $serviceManager->run();

        $this->info('Generate service v2');
    }

    /**
     * @throws \Exception
     */
    protected function generateFile($type, $options)
   {
       $options['save_file'] = false;

       $metaData = parent::generateFile($type,$options);

       $repositoryPaths = $options['repository_paths'] ?? [];
       foreach ($repositoryPaths as $filePath){

           [$varName,$type] = $this->normalizeParamFromFilePath($filePath);
           $this->addPropertyToClass($varName,$type,'public');
           $this->addParamToConstruct($varName,$type);

       }

       $servicePaths = $options['service_paths'] ?? [];
       foreach ($servicePaths as $filePath){

           [$varName,$type] = $this->normalizeParamFromFilePath($filePath);
           $this->addPropertyToClass($varName,$type);
           $this->addParamToConstruct($varName,$type);

       }

       $this->storeFile($metaData['path']);
   }
}
