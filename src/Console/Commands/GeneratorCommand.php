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



    protected function normalizeClassName($className){
        $className = Str::lower($className);
        $className = Str::ucfirst($className);
        return $className;
    }

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

    protected function generateFile($type,$options){

        $className = $options['class_name'];

        $metaData = $this->getMetaData($type);

        if (!$metaData) return false;

        $parentDir = $metaData['parent_dir'];
        $suffix = $metaData['suffix'];


        Artisan::call("make:class $parentDir/$className$suffix");
        $classContent = new ClassType($className.$suffix);
        $path = app_path("$parentDir/$className$suffix.php");

        file_put_contents($path,"<?php \n\n$classContent");
        return true;

    }

    private function getMetaData($type){

        $metaData = null;

        switch (Str::lower($type)){
            case 'repository':
                $metaData['parent_dir'] = 'Repositories';
                $metaData['suffix'] = 'Repository';
                break;

            default: return $metaData;
        }


        return $metaData;
    }

}
