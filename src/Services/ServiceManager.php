<?php

namespace josanangel\ServiceRepositoryManager\Services;

use josanangel\ServiceRepositoryManager\Services\Abtracts\CreationManager;

class ServiceManager extends CreationManager
{

    protected $serviceDependencies;
    protected $repositoryDependencies;

    public function __construct($className)
    {
        parent::__construct($className);
        $this->parentDir = 'Services';
        $this->suffix = 'Service';
        $this->repositoryDependencies = collect();
        $this->serviceDependencies = collect();
    }

    public function run()
    {
        $this->resolveDependencies();
        $this->generateFile();
    }

    protected function resolveDependencies()
    {
       $this->getRepositoryDependencies()->each(function ($repositoryName){
           $auxManager = new RepositoryManager($repositoryName);
           $auxManager->run();
           [$varName,$type] = $auxManager->normalizeParamFromFilePath($repositoryName);
           $this->addPropertyToClass($varName,$type,'protected');
//           $this->addParamToConstruct($varName,$type);
       });

//        $this->getServiceDependencies()->each(function ($serviceName){
//            $auxManager = new ServiceManager($serviceName);
//            $auxManager->run();
//        });
    }

    public function addRepositoriesAsDependencies($repositories)
    {
        $this->repositoryDependencies = $repositories;
    }
    public function addServicesAsDependencies( $services)
    {
        $this->serviceDependencies = $services;
    }

    protected function getServiceDependencies()
    {
        return $this->serviceDependencies;
    }
    protected function getRepositoryDependencies()
    {
        return $this->repositoryDependencies;
    }

}
