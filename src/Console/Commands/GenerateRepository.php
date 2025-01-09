<?php

/**
 * Implementation of GeneratorCommand abstract class, dedicated to generate Repository files.
 * This file is handled by RepositoryManager, this one contains complex and configurable logic through repository file creation
 *
 * Required Params:
 *  + {name} : Name of repository you are going to create.
 *
 * Optional Params:
 *  + {--module} : Indicate where is going to generate file(s)
 *  + {--crud} : Specify if the repository should include predefined CRUD functions
 */

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use josanangel\ServiceRepositoryManager\Services\RepositoryManager;

class GenerateRepository extends GeneratorCommand
{
    protected $signature = 'make:repository {name} {--module=} {--crud}';

    protected $description = 'Generate a repository class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $name = $this->argument('name');
        $module = $this->option('module');
        $crud = $this->option('crud');

        $repositoryManager = new RepositoryManager($name,$module);
        $repositoryManager->beforeRun();

        if ($crud){
            $repositoryManager->addAttributeToClass('model');
            $repositoryManager->generateCrudMethods();
        }

        $repositoryManager->run();

        $this->info('Repository has been created successfully.');
    }


}
