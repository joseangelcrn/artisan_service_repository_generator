<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateService extends GeneratorCommand
{
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

        $this->generateFile('service',[
           'class_name'=>$name,
           'repository_paths'=>$repositoryPaths,
            'service_paths'=>$servicePaths
        ]);
        $this->info("Service '$serviceClassName' has been created successfully");
    }


    private function generateRepositoriesIfNotExists($repositories){
        $repositories = $repositories->map(function ($repository){
            $repository = $this->normalizeClassName($repository);
            $fileName = $repository."Repository.php";
            $path =  app_path("Repositories/$fileName");
            if (!File::exists($path)){
                $this->warn("Repository '$repository' doesnt exist, creating it");
                Artisan::call("make:repository" ,['name'=>$repository]);
            }
            //to use in code as string...
            $relativePath = "App\\Repositories\\$fileName";
            return $relativePath;
        });
        return $repositories;
    }

    private function generateServiceIfNotExists($services){
        $services = $services->map(function ($service){
            $service = $this->normalizeClassName($service);
            $fileName = $service."Service.php";
            $path =  app_path("Services/$fileName");
            if (!File::exists($path)){
                $this->warn("Service '$service' doesnt exist, creating it");
                Artisan::call("make:service" ,['name'=>$service]);
            }
            //to use in code as string...
            $relativePath = "App\\Services\\$fileName";
            return $relativePath;
        });
        return $services;
    }
}
