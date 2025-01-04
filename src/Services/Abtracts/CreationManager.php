<?php

namespace josanangel\ServiceRepositoryManager\Services\Abtracts;

use Illuminate\Support\Str;
use josanangel\ServiceRepositoryManager\Interfaces\CreationManagerActions;
use josanangel\ServiceRepositoryManager\Services\Abtracts\Traits\HasConfig;
use josanangel\ServiceRepositoryManager\Services\Abtracts\Traits\HasNormalizer;
use Nette\PhpGenerator\Printer;
use stdClass;
use josanangel\ServiceRepositoryManager\Services\Abtracts\Traits\HasClassBuilder;
abstract class CreationManager implements CreationManagerActions
{

    use HasClassBuilder,
        HasConfig,
        HasNormalizer;

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

    protected $config;

    protected $module;


    public function __construct(string $rawClassName, string $module = null)
    {

        $this->typeResolving = false;

        $this->attributes = collect();
        $this->constructorParams = collect();
        $this->uses = collect();


        $this->rawClassName = $rawClassName;
        $this->module = $module;
    }


    function generateFile()
    {
        $previousPath = app_path($this->getParentDir());

        if (!is_dir($previousPath)){
            mkdir($previousPath,0755,true);
        }

        $path=$previousPath.DIRECTORY_SEPARATOR.$this->normalizedClassName.".php";

        if ($this->typeResolving){
            $content = $this->namespaceBuilder;
        }else{
            $printer = new Printer; // or PsrPrinter
            $printer->setTypeResolving(false);
            $content = $printer->printNamespace($this->namespaceBuilder);
        }

        file_put_contents($path,"<?php \n\n$content");
    }


    protected function getParentDir()
    {
        $moduleDir = '';
        if ($this->configIsModulesEnabled()){
            $moduleDir = 'Modules'.DIRECTORY_SEPARATOR.$this->module.DIRECTORY_SEPARATOR;
        }
        return $moduleDir.$this->parentDir;
    }



    /**
     * Runner
     * @throws \Exception
     */

    public function run()
    {
        $this->normalizeClassName();
        $this->generateNameSpace();
        $this->instanceClassBuilder();
        $this->generateConstructor();

        $this->resolveVariables();
        $this->generateFile();
    }
}

