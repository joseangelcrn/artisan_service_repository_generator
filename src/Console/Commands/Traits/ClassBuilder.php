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
    protected function addParamToConstruct( $varName,$type): void
    {

        $constructor = $this->getConstructor();

        //property
        $this->classContent->addProperty($varName)->setType($type)->setProtected();
        //param
        $constructor->addParameter($varName)->setType($type);
        //set param to props
        $constructor->addBody("\$this->$varName = \$$varName;");
    }
}
