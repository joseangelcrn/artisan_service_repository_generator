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

        $shouldOverride = $this->shouldOverrideIfExists('repository',[
            'class_name' => $name,
            'additional_message' => "Repository already exists"
        ]);

        if (!$shouldOverride){
            return false;
        }

        $this->generateFile('repository',[
           'class_name'=>$name
        ]);
        $this->info("Repository '$repositoryClassName' has been created successfully");
    }


}
