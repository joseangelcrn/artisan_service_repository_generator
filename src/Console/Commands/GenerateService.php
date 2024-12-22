<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;


use josanangel\ServiceRepositoryManager\Console\Commands\Traits\AuxGenerator;

class GenerateService extends GeneratorCommand
{
    use AuxGenerator;

    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'make:service {name} {--repositories=} {--services=}';

    // La descripción del comando
    protected $description = 'Generate a service class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $name = $this->normalizeClassName($name);
        $serviceClassName = $name."Service";

        $repositories = $this->option('repositories',[]);
        $repositories = explode(',',$repositories);
        $repositories = collect($repositories);
        $repositoryPaths = collect();

        $services = $this->option('services',[]);
        $services = explode(',',$services);
        $services = collect($services);
        $servicePaths = collect();

        if ($repositories->filter()->isNotEmpty()){
           $repositoryPaths = $this->generateRepositoriesIfNotExists($repositories);
        }

        if ($services->filter()->isNotEmpty()){
            $servicePaths = $this->generateServiceIfNotExists($services);
        }

//todo remove comments when tests finish
//        $shouldOverride = $this->shouldOverrideIfExists('service',[
//            'class_name' => $name,
//            'additional_message' => "Service already exists"
//        ]);
//
//        if (!$shouldOverride){
//            return false;
//        }

        $this-> generateFile('service',[
           'class_name'=>$name,
           'repository_paths'=>$repositoryPaths,
            'service_paths'=>$servicePaths
        ]);
        $this->info("Service '$serviceClassName' has been created successfully");
    }

   protected function generateFile($type,$options)
   {
       $options['save_file'] = false;

       $metaData = parent::generateFile($type,$options);

       $repositoryPaths = $options['repository_paths'] ?? [];
       foreach ($repositoryPaths as $filePath){

           [$varName,$type] = $this->normalizeParamFromFilePath($filePath);
           $this->addParamToConstruct($varName,$type);

       }

       $servicePaths = $options['service_paths'] ?? [];
       foreach ($servicePaths as $filePath){

           [$varName,$type] = $this->normalizeParamFromFilePath($filePath);
           $this->addParamToConstruct($varName,$type);

       }

       $this->storeFile($metaData['path']);
   }
}
