<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands\Traits;

use Illuminate\Support\Str;

trait Normalizer
{
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
     * @param $filePath
     * @return array
     *
     * Normalize var structure before insert to construct
     */
    protected function normalizeParamFromFilePath($filePath){
        $filePathParts = explode('\\',$filePath);
        $type = str_replace('.php','',array_pop($filePathParts));
        $varName =Str::camel($type);

        return [
            $varName,
            $type
        ];
    }
}
