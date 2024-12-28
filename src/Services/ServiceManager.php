<?php

namespace josanangel\ServiceRepositoryManager\Services;

use josanangel\ServiceRepositoryManager\Services\Abtracts\CreationManager;

class ServiceManager extends CreationManager
{

    public function __construct($rawClassName)
    {
        $this->parentDir = 'Services';
        $this->suffix = 'Service';
        $this->namespace = 'App\Services';
        parent::__construct($rawClassName);

    }

    public function run()
    {
        $this->resolveVariables();
        $this->generateFile();

    }



}
