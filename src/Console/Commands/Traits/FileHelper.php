<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

trait FileHelper
{
    /**
     * @param $parentDir
     * @param $className
     * @param $suffix
     * @return void
     *
     * Generate a placeholder file which will be overwritten later
     */
    protected function generateTempFile($parentDir,$className,$suffix = null)
    {
        Artisan::call("make:class $parentDir/$className$suffix");

    }

    /**
     * @param $parentDir
     * @param $className
     * @param $suffix
     * @return string
     *
     * Generate path where file will be stored
     */
    protected function getFilePath($parentDir,$className,$suffix = null)
    {
        return app_path("$parentDir/$className$suffix.php");
    }

    /**
     * @param $path
     * @return void
     *
     * Store content into previous created  file
     */
    protected function storeFile($path = null){
        file_put_contents($path,"<?php \n\n$this->classContent");
    }
}
