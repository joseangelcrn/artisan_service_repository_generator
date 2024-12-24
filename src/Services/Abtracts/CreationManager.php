<?php

namespace josanangel\ServiceRepositoryManager\Services\Abtracts;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Nette\PhpGenerator\ClassType;

abstract class CreationManager
{
    protected $rawClassName;
    protected $normalizedClassName;
    protected $parentDir;
    protected $suffix;

    protected $classBuilder;



    public function __construct($className)
    {
        $this->rawClassName = $className;
        $this->normalizeClassName();
    }


    protected function normalizeClassName(){

        $className = Str::lower($this->rawClassName);
        $className = Str::replace('repository','',$className);
        $className = Str::replace('service','',$className);
        $className = Str::ucfirst($className);
        $this->normalizedClassName = $className;

        $this->applySuffix();
    }

    protected function normalizeParamFromFilePath($path)
    {
        $filePathParts = explode('\\',$path);
        $type = str_replace('.php','',array_pop($filePathParts));
        $varName =Str::camel($type);

        return [
            $varName,
            $type
        ];
    }
    protected function applySuffix()
    {
        $this->normalizedClassName = $this->normalizedClassName.$this->getSuffix();
    }

    protected function generateFile($saveFile = true)
    {
        $this->generateTempFile();
        $this->instanceClassBuilder();
        $this->generateConstruct();

        if ($saveFile){
            $this->storeFile();
        }

    }


    protected function generateTempFile()
    {
        $parentDir = $this->getParentDir();
        $normalizedClassName = $this->getNormalizedClassName();
        Artisan::call("make:class $parentDir/$normalizedClassName");
    }

    protected function storeFile()
    {
        $path = $this->getFilePath();
        $classBuilder = $this->getClassBuilder();

        file_put_contents($path,"<?php \n\n$classBuilder");

    }
    /**
     * Class Builder
     */
    //todo... adapt ClassBuilder trait

    protected function instanceClassBuilder()
    {
        $normalizedClassName = $this->getNormalizedClassName();
        $this->classBuilder =  new ClassType($normalizedClassName);
    }
    protected function generateConstruct()
    {
        return $this->getClassBuilder()->addMethod('__construct')->setPublic();
    }
    protected function addPropertyToClass($varName,string $type,$scope)
    {
        $allowedScopes = ['protected','private','public'];
        if (!in_array($scope,$allowedScopes)){
            throw new \Exception("Inserted scope is not available ('$scope'), allowed scopes = ".implode(',',$allowedScopes));
        }


        //todo.. apply scope later..
        $this->getClassBuilder()->addProperty($varName)->setType($type)->setProtected();
    }

    protected function addParamToConstruct($paramName,$type,$attributeName = null)
    {
        if (!$attributeName){
            $attributeName = $paramName;
        }

        $constructor = $this->getConstructor();

        //param
        $constructor->addParameter($paramName)->setType($type);

        //set param to attribute
        $constructor->addBody("\$this->$attributeName = \$$paramName;");
    }
    protected function getConstructor()
    {
        return $this->getClassBuilder()->getMethod('__construct');
    }


    protected function getClassBuilder()
    {
        return $this->classBuilder;
    }


    /**
     * Getters
     */

    protected function getSuffix()
    {
        return $this->suffix;
    }

    protected function getParentDir()
    {
        return $this->parentDir;
    }

    protected function getNormalizedClassName()
    {
        if (!$this->normalizedClassName){
            $this->normalizeClassName();
        }

        return $this->normalizedClassName;
    }


    protected function getFilePath()
    {
        $parentDir = $this->getParentDir();
        $normalizedClassName = $this->getNormalizedClassName();
        return app_path("$parentDir/$normalizedClassName.php");
    }
}
