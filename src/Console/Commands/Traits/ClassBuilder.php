<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands\Traits;

use Nette\PhpGenerator\ClassType;

trait ClassBuilder
{
    protected $classContent;

    protected function instanceClassBuilder($className,$suffix=null)
    {
        $this->classContent =  new ClassType($className.$suffix);
    }
    protected function getConstructor()
    {
        return $this->classContent->getMethod('__construct');
    }



    protected function generateConstruct()
    {
        return $this->classContent->addMethod('__construct')->setPublic();
    }

    /**
     * @param $varName
     * @param $type
     * @param string $scope
     * @return void
     * @throws \Exception
     *
     * Add new property to class
     */
    protected function addPropertyToClass($varName, $type, string $scope = 'protected'): void
    {
        if (!in_array($scope,['protected','private','public'])){
            throw new \Exception("inserted scope is not available ('$scope')");
        }
        $this->classContent->addProperty($varName)->setType($type)->setProtected();
    }

    /**
     * @param $varName
     * @param $type
     * @return void
     *
     * Add new param to construct
     */
    protected function addParamToConstruct( $varName,$type): void
    {

        $constructor = $this->getConstructor();

        //param
        $constructor->addParameter($varName)->setType($type);
        //set param to props
        $constructor->addBody("\$this->$varName = \$$varName;");
    }
}
