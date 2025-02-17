<?php

/**
 * This class extends CreationManager in order to take advantage of all the resources it provides by CreationManager class,
 * simplifying the creation of services as much as possible.
 */

namespace josanangel\ServiceRepositoryManager\Services;

use josanangel\ServiceRepositoryManager\Services\Abtracts\CreationManager;

class ServiceManager extends CreationManager
{

    public function __construct($rawClassName,$module = null)
    {
        $this->loadConfig();

        $this->parentDir = $this->config['services']['path'];
        $this->suffix = $this->config['services']['suffix'];
        $this->namespace = $this->config['services']['namespace'];
        parent::__construct($rawClassName,$module);

    }
}
