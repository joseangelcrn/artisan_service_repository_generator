<?php

namespace josanangel\ServiceRepositoryManager\Services\Abtracts;

use Illuminate\Support\Str;
use josanangel\ServiceRepositoryManager\Interfaces\CreationManagerActions;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
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

    protected $namespace;
    protected $namespaceBuilder;

    protected $uses;

    protected $typeResolving;


    public function __construct(string $rawClassName)
    {

        $this->typeResolving = false;

        $this->attributes = collect();
        $this->constructorParams = collect();
        $this->uses = collect();


        $this->rawClassName = $rawClassName;
        $this->normalizeClassName();
        $this->generateNameSpace();
        $this->instanceClassBuilder();
        $this->generateConstructor();
    }

     function generateConstructor()
    {
        $this->classBuilder->addMethod('__construct')->setPublic();
    }

    function generateNameSpace()
    {
        if ($this->namespace){
            $this->namespaceBuilder = new PhpNamespace($this->namespace);
            $this->classBuilder = $this->namespaceBuilder->addClass($this->normalizedClassName);
        }
    }


    function instanceClassBuilder()
    {
        if ($this->namespace) return ;
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

    function addAttributeToClass($varName,$varType,$nameSpace = null)
    {
        $newAttribute = new StdClass();
        $newAttribute->name = $varName;
        $newAttribute->type = $varType;

        if ($nameSpace){
            $newAttribute->namespace = $nameSpace;
        }


        $this->attributes->push($newAttribute);
    }

    function addParamToConstructor($varName,$varType,$nameSpace = null)
    {
        $newParam = new StdClass();
        $newParam->name = $varName;
        $newParam->type = $varType;

        if ($nameSpace){
            $newParam->namespace = $nameSpace;
        }

        $this->constructorParams->push($newParam);
    }


    function resolveVariables()
    {
        $constructor = $this->getConstruct();

        foreach ($this->attributes as $index=>$attribute){
            $param = $this->constructorParams->get($index);

            if ($this->namespace){
                if (isset($attribute->namespace) and $this->namespace != $attribute->namespace){
                    $this->addUseIfNotExists($attribute->namespace.'\\'.$attribute->type);
                }
                if (isset($param->namespace) and $this->namespace != $param->namespace){
                    $this->addUseIfNotExists($param->namespace.'\\'.$param->type);
                }
            }
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


        if ($this->typeResolving){
            $content = $this->namespaceBuilder;
        }else{
            $printer = new Printer; // or PsrPrinter
            $printer->setTypeResolving(false);
            $content = $printer->printNamespace($this->namespaceBuilder);
        }

        file_put_contents($path,"<?php \n\n$content");
    }


    protected function getConstruct()
    {
        return $this->classBuilder->getMethod('__construct');
    }

    protected function addUseIfNotExists($path)
    {
        if ($path and !$this->uses->contains($path)){
            $this->namespaceBuilder->addUse($path);
            $this->uses->push($path);
        }
    }

    public function getVariable()
    {
        return Str::camel($this->normalizedClassName);
    }
    public function getType()
    {
        return Str::ucfirst(Str::camel($this->normalizedClassName));
    }

    public function getNameSpace()
    {
        return $this->namespace;
    }
}

