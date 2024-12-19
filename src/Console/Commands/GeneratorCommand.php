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

    protected function shouldOverrideIfExists($path, $additionalMessage){
        if (File::exists($path)){
            $shouldOverride =$this->ask("$additionalMessage, do you want override it ? [y/N]",false);
            if (!in_array(Str::lower($shouldOverride),['y','yes'])) {
                $this->info('Aborting process..');
                return false;
            }
        }
        return true;
    }

    protected function generateFile($type,$options){

        $className = $options['class_name'];

        $parentDir = null;
        $suffix = null;

        switch (Str::lower($type)){
            case 'repository':
                $parentDir = 'Repositories';
                $suffix='Repository';
                break;
            default:
                return false;
        }

        Artisan::call("make:class $parentDir/$className$suffix");
        $classContent = new ClassType($className.$suffix);
        $path = app_path("$parentDir/$className$suffix.php");

        file_put_contents($path,"<?php \n\n$classContent");
        return true;

    }

}
