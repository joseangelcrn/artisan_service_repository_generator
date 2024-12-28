<?php

namespace josanangel\ServiceRepositoryManager\Services;

use josanangel\ServiceRepositoryManager\Services\Abtracts\CreationManager;

class RepositoryManager extends CreationManager
{

    public function __construct($rawClassName)
    {
        $this->parentDir = 'Repositories';
        $this->suffix = 'Repository';
        $this->namespace = 'App\Repositories';
        parent::__construct($rawClassName);

    }

    public function run()
    {
        $this->resolveVariables();
        $this->generateFile();
    }



}
