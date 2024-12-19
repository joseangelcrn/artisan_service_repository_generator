<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GenerateService extends GeneratorCommand
{
    // El nombre del comando que ejecutarás en la consola
    protected $signature = 'make:service {name}';

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

        $shouldOverride = $this->shouldOverrideIfExists('service',[
            'class_name' => $name,
            'additional_message' => "Service already exists"
        ]);

        if (!$shouldOverride){
            return false;
        }

        $this->generateFile('service',[
           'class_name'=>$name
        ]);
        $this->info("Service '$serviceClassName' has been created successfully");
    }


}
