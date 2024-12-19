<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateService extends GeneratorCommand
{
    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'make:service {name} {--repositories=}';

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

        $repositories = $this->option('repositories');
        $repositories = explode(',',$repositories);
        $repositories = collect($repositories);

        if ($repositories->isNotEmpty()){

            $repositories = $repositories->map(function ($repository){
                $path =  app_path("Repositories/$repository.php");
                if (!File::exists($path)){
                    $this->warn("Repository '$repository' doesnt exist, creating it");
                    Artisan::call("make:repository" ,['name'=>$repository]);
                }
                return $path;
            });



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
           'class_name'=>$name
        ]);
        $this->info("Service '$serviceClassName' has been created successfully");
    }


}
