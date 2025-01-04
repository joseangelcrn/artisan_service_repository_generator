<?php

namespace josanangel\ServiceRepositoryManager\Services\Abtracts\Traits;


use Illuminate\Support\Str;

trait HasNormalizer
{
    function normalizeClassName()
    {

        $className = $this->rawClassName;
        $className = Str::lower($className);
        $className = Str::replace('repository','',$className);
        $className = Str::replace('service','',$className);
        $className = Str::ucfirst($className);
        $this->normalizedClassName = $className;

        if ($this->suffix){
            $this->applySuffix();
        }
    }

    function applySuffix()
    {
        $this->normalizedClassName .=$this->suffix;
    }
}
