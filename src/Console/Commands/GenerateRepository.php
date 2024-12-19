<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GenerateRepository extends GeneratorCommand
{
    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'make:repository {name}';

    // La descripción del comando
    protected $description = 'Generate a repository class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $name = $this->normalizeClassName($name);
        $repositoryClassName = $name."Repository";
        $filePath = app_path("Repositories/$repositoryClassName.php");


        if (! $this->shouldOverrideIfExists($filePath,"Repository already exists")){
            return false;
        }
        File::delete($filePath);
        $this->generateFile('repository',[
           'class_name'=>$name
        ]);
        $this->info("Repository '$repositoryClassName' has been created successfully");
    }


}
