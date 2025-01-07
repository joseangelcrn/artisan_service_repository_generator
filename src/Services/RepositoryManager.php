<?php

/**
 * This class extends CreationManager in order to take advantage of all the resources it provides by CreationManager class,
 * simplifying the creation of repositories as much as possible.
 */

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
}
