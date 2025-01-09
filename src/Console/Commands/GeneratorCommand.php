<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Console\Command;
use josanangel\ServiceRepositoryManager\Console\Commands\Traits\ClassBuilder;
use josanangel\ServiceRepositoryManager\Console\Commands\Traits\FileHelper;
use josanangel\ServiceRepositoryManager\Console\Commands\Traits\Normalizer;

class GeneratorCommand extends Command
{

    protected function getMultipleValuesFromOption($key,$default = [])
    {
        $options = $this->option($key,$default);
        $options = explode(',',$options);
        $options = collect($options);
        $options = $options->filter();

        return $options;
    }
}
