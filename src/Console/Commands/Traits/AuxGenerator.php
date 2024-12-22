<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

trait AuxGenerator
{
    /**
     * @param $repositories
     * @return mixed
     *
     * Generate additional repositories if executed command need it
     */
    protected function generateRepositoriesIfNotExists($repositories){
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

    /**
     * @param $services
     * @return mixed
     *
     * Generate additional services if executed command need it
     */
    protected function generateServiceIfNotExists($services){
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
