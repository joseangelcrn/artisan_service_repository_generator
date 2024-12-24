<?php

namespace josanangel\ServiceRepositoryManager\Services;

use josanangel\ServiceRepositoryManager\Services\Abtracts\CreationManager;

class RepositoryManager extends CreationManager
{

    public function __construct($className)
    {
        parent::__construct($className);
        $this->parentDir = 'Repositories';
        $this->suffix = 'Repository';
    }

    public function run()
    {
        $this->generateFile();
    }
}
