<?php

namespace josanangel\ServiceRepositoryManager\Services;

use josanangel\ServiceRepositoryManager\Services\Abtracts\CreationManager;

class RepositoryManager extends CreationManager
{

    public function __construct($rawClassName)
    {
        $this->parentDir = 'Repositories';
        $this->suffix = 'Repository';
        parent::__construct($rawClassName);

    }

    public function run()
    {
        $this->setConstructorParamsToAttributes();
        $this->generateFile();
    }



}
