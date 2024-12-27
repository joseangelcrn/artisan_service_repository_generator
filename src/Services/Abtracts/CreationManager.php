<?php

namespace josanangel\ServiceRepositoryManager\Services\Abtracts;

use Illuminate\Support\Str;
use josanangel\ServiceRepositoryManager\Interfaces\CreationManagerActions;
use Nette\PhpGenerator\ClassType;
use stdClass;

abstract class CreationManager implements CreationManagerActions
{
    protected $rawClassName;
    protected $normalizedClassName;

    protected $attributes;
    protected $constructorParams;

    protected $suffix;

    protected $classBuilder;

    protected $path;

    protected $parentDir;


    public function __construct(string $rawClassName)
    {

        $this->attributes = collect();
        $this->constructorParams = collect();


        $this->rawClassName = $rawClassName;
        $this->normalizeClassName();
        $this->instanceClassBuilder();
        $this->generateConstructor();
    }

     function generateConstructor()
    {
        $this->classBuilder->addMethod('__construct')->setPublic();
    }


    function instanceClassBuilder()
    {
        $this->classBuilder =  new ClassType($this->normalizedClassName);
    }

    function normalizeClassName()
    {

        $className = Str::lower($this->rawClassName);
        $className = Str::replace('repository','',$className);
        $className = Str::replace('service','',$className);
        $className = Str::ucfirst($className);
        $this->normalizedClassName = $className;

        $this->applySuffix();
    }

    function applySuffix()
    {
        if ($this->suffix){
            $this->normalizedClassName .=$this->suffix;
        }
    }

    function addAttributeToClass($varName,$varType)
    {
        $newAttribute = new StdClass();
        $newAttribute->name = $varName;
        $newAttribute->type = $varType;

        $this->attributes->push($newAttribute);
    }

    function addParamToConstructor($varName,$varType)
    {
        $newParam = new StdClass();
        $newParam->name = $varName;
        $newParam->type = $varType;

        $this->constructorParams->push($newParam);
    }


    function resolveVariables()
    {
        $constructor = $this->getConstruct();

        foreach ($this->attributes as $index=>$attribute){
            $param = $this->constructorParams->get($index);

            //Generate class attribute
            if ($attribute->name){
                $this->classBuilder->addProperty($attribute->name)->setType($attribute->type)->setProtected();
            }

            //Generate constructor param
            if ($param){
                $constructor->addParameter($param->name)->setType($param->type);
            }

            //Set property to param
            if ($attribute->name and $param){
                $attributeName = $attribute->name;
                $paramName = $param->name;
                $constructor->addBody("\$this->$attributeName = \$$paramName;");
            }

        }
    }

    function generateFile()
    {
        $path = app_path($this->parentDir.'/'.$this->normalizedClassName.".php");
        file_put_contents($path,"<?php \n\n$this->classBuilder");
    }


    protected function getConstruct()
    {
        return $this->classBuilder->getMethod('__construct');
    }

    public function getVariable()
    {
        return Str::camel($this->normalizedClassName);
    }
    public function getType()
    {
        return Str::ucfirst(Str::camel($this->normalizedClassName));
    }
}

