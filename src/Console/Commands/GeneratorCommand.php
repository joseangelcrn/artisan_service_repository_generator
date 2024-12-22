<?php

namespace josanangel\ServiceRepositoryManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use josanangel\ServiceRepositoryManager\Console\Commands\Traits\ClassBuilder;
use josanangel\ServiceRepositoryManager\Console\Commands\Traits\FileHelper;
use josanangel\ServiceRepositoryManager\Console\Commands\Traits\Normalizer;
use Nette\PhpGenerator\ClassType;

class GeneratorCommand extends Command
{

    use ClassBuilder,
        Normalizer,
        FileHelper
        ;


    public function __construct()
    {
        parent::__construct();
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
     * @return array
     */
    protected function generateFile($type,$options){

        $className = $options['class_name'];
        $saveFile = $options['save_file'] ?? true;

        $metaData = $this->getMetaData($type);

        if (!$metaData) return false;

        $parentDir = $metaData['parent_dir'];
        $suffix = $metaData['suffix'];

        $this->generateTempFile($parentDir,$className,$suffix);

        $this->instanceClassBuilder($className,$suffix);

        $this->generateConstruct();

        $path = $this->getFilePath($parentDir,$className,$suffix);

        if ($saveFile){
            $this->storeFile($path);
        }

        return [
            'path'=> $path
        ];

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

}
