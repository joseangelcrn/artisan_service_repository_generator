<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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
}
