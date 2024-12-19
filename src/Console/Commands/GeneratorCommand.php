<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

}
