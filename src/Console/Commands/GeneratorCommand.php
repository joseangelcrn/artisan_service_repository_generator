<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nette\PhpGenerator\ClassType;

class GeneratorCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Normalize input className avoiding problematic nomenclatures
     *
     * @param $className
     * @return string
     */
    protected function normalizeClassName($className) : String {
        $className = Str::lower($className);
        $className = Str::replace('repository','',$className);
        $className = Str::replace('service','',$className);
        $className = Str::ucfirst($className);
        return $className;
    }

    /**
     *
     * Check if new file already exists, if file does, a prompt will be displayed to ask if file should be overridden
     *
     * @param $type
     * @param $options
     * @return bool
     */
    protected function shouldOverrideIfExists($type, $options){
        $metaData = $this->getMetaData($type);

        if (!$metaData) return false;

        $className = $options['class_name'];
        $additionalMessage = $options['additional_message'];

        $parentDir = $metaData['parent_dir'];
        $suffix = $metaData['suffix'];

        $path = app_path("$parentDir/$className$suffix.php");

        if (File::exists($path)){
            $shouldOverride =$this->ask("$additionalMessage, do you want override it ? [y/N]",false);
            if (!in_array(Str::lower($shouldOverride),['y','yes'])) {
                $this->info('Aborting process..');
                return false;
            }
        }
        File::delete($path);
        return true;
    }

    /**
     * Generate a file with all received specifications (Repository and Service case)
     *
     * @param $type
     * @param $options
     * @return bool
     */
    protected function generateFile($type,$options){

        $className = $options['class_name'];

        $metaData = $this->getMetaData($type);

        if (!$metaData) return false;

        $parentDir = $metaData['parent_dir'];
        $suffix = $metaData['suffix'];


        Artisan::call("make:class $parentDir/$className$suffix");

        $classContent = new ClassType($className.$suffix);
        $classContent->addMethod('__construct')->setPublic();

        $path = app_path("$parentDir/$className$suffix.php");

        if ($type === 'service'){
            $repositoryPaths = $options['repository_paths'] ?? [];
            foreach ($repositoryPaths as $filePath){
                $filePathParts = explode('\\',$filePath);
                $repositoryType = str_replace('.php','',array_pop($filePathParts));
                $varName =Str::camel($repositoryType);

                $this->addParamToConstruct($classContent,$varName,$repositoryType);

            }
        }

        file_put_contents($path,"<?php \n\n$classContent");
        return true;

    }

    /**
     * Retrieve  according metadata for services and repositories
     *
     * @param $type
     * @return array|null
     */
    private function getMetaData($type){

        $metaData = null;

        switch (Str::lower($type)){
            case 'repository':
                $metaData['parent_dir'] = 'Repositories';
                $metaData['suffix'] = 'Repository';
                break;
            case 'service':
                $metaData['parent_dir'] = 'Services';
                $metaData['suffix'] = 'Service';
                break;

            default: return $metaData;
        }


        return $metaData;
    }

//    private function generateClassContent($type,$options){
//        $classContent = new ClassType($className.$suffix);
//        $constructor = $classContent->addMethod('__construct')
//            ->setPublic();
//
//        $path = app_path("$parentDir/$className$suffix.php");
//
//        if ($type === 'service'){
//            $repositories = $options['repositories'];
//            echo 1;
//        }
//    }


    private function addParamToConstruct($classContent, $varName,$type): void
    {

        $constructor = $classContent->getMethod('__construct');

        //property
        $classContent->addProperty($varName)->setType($type)->setProtected();
        //param
        $constructor->addParameter($varName)->setType($type);
        //set param to props
        $constructor->addBody("\$this->$varName = \$$varName;");
    }
}
