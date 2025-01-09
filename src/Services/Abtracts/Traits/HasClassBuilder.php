<?php

namespace josanangel\ServiceRepositoryManager\Services\Abtracts\Traits;

use Illuminate\Support\Str;
use stdClass;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\ClassType;

trait HasClassBuilder
{

    function instanceClassBuilder()
    {
        if ($this->namespace) return ;
        $this->classBuilder =  new ClassType($this->normalizedClassName);
    }

    function generateConstructor()
    {
        $this->classBuilder->addMethod('__construct')->setPublic();
    }

    function generateNameSpace()
    {
        if ($this->namespace){

            if ($this->configIsModulesEnabled()){
                if (!$this->hasNamespaceModuleKeyWord()){
                    throw new \Exception('Namespace require a "{module_name}" keyword if module config is enabled.') ;
                }
                else if (!$this->module){
                    throw new \Exception('Module can not be null if module config is enabled.') ;
                }
                else {
                    $this->namespace = Str::replace('{module_name}',$this->module,$this->namespace);
                }
            }

            $this->namespaceBuilder = new PhpNamespace($this->namespace);
            $this->classBuilder = $this->namespaceBuilder->addClass($this->normalizedClassName);
        }
    }


    protected function addUseIfNotExists($path)
    {
        if ($path and !$this->uses->contains($path)){
            $this->namespaceBuilder->addUse($path);
            $this->uses->push($path);
        }
    }



    function addAttributeToClass($varName,$varType = null,$nameSpace = null)
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

    public function getNameSpace()
    {
        return $this->namespace;
    }

}
