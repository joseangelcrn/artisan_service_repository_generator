<?php

/**
 * CreationManager groups all generational functions to deploy a Repository/Service files according to specific configuration.
 * This class contains all complex logic which is responsible for managing the creation process of both types of files.
 *
 * Additionally, this class is organized by different traits according to its functions:
 *
 *  - HasClassBuilder: Manages the file structure creation.
 *  - HasConfig: Handles all logic related to possibles configuration methods.
 *  - HasNormalizer: Responsible for sanitizing different values.
 */

/**
 * BEYOND EXISTING CONFIGURATION
 *
 * If it was necessary, you might generate a new abstract class, extending this one or not and repeat the same process
 * what is implemented right now, with entirely different logic and purposes keeping a clean and modularized code exploring
 *  news alternatives of creation, functionality extensions, etc.
 */

namespace josanangel\ServiceRepositoryManager\Services\Abtracts;

use josanangel\ServiceRepositoryManager\Interfaces\CreationManagerActions;
use josanangel\ServiceRepositoryManager\Services\Abtracts\Traits\HasConfig;
use josanangel\ServiceRepositoryManager\Services\Abtracts\Traits\HasNormalizer;
use Nette\PhpGenerator\Printer;
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

    protected $flagBeforeRun;


    public function __construct(string $rawClassName, string $module = null)
    {

        $this->typeResolving = false;

        $this->flagBeforeRun = false;

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


    public function beforeRun()
    {
        $this->normalizeClassName();
        $this->generateNameSpace();
        $this->instanceClassBuilder();
        $this->generateConstructor();
        $this->flagBeforeRun = true;
    }

    /**
     * Ordered process to generate any file, it can be configurable if needed, overriding this 'run' method from child
     * file.
     * Runner
     * @throws \Exception
     */

    public function run()
    {
        if (!$this->flagBeforeRun){
            $this->beforeRun();
        }
        $this->resolveVariables();
        $this->generateFile();
    }
}

