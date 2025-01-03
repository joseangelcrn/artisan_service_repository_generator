<?php

namespace josanangel\ServiceRepositoryManager\Services;

use josanangel\ServiceRepositoryManager\Services\Abtracts\CreationManager;

class RepositoryManager extends CreationManager
{

    public function __construct($rawClassName,$module = null)
    {
        $this->loadConfig();

        $this->parentDir = $this->config['repositories']['path'];
        $this->suffix = $this->config['repositories']['suffix'];
        $this->namespace = $this->config['repositories']['namespace'];
        parent::__construct($rawClassName,$module);

    }

    public function run()
    {
        $this->resolveVariables();
        $this->generateFile();
    }



}
