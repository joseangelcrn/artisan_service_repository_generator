<?php

namespace josanangel\ServiceRepositoryManager\Services\Abtracts\Traits;

trait HasConfig
{

    protected function loadConfig()
    {
        $this->config = config('service_repository_manager');
    }

    public function configIsModulesEnabled()
    {
        return $this->getConfig()['modules'];
    }

    public function hasNamespaceModuleKeyWord()
    {
        return str_contains($this->namespace,'{module_name}');
    }

    public function getConfig()
    {
        return $this->config;
    }
}
