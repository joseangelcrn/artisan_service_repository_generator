<?php

/**
 * This class extends CreationManager in order to take advantage of all the resources it provides by CreationManager class,
 * simplifying the creation of repositories as much as possible.
 */

namespace josanangel\ServiceRepositoryManager\Services;

use josanangel\ServiceRepositoryManager\Services\Abtracts\CreationManager;

class RepositoryManager extends CreationManager
{

    public function __construct($rawClassName,$module = null)
    {
        $this->loadConfig();

        $this->parentDir = $this->config['repositories']['path'];
        $this->suffix = $this->config['repositories']['suffix'];
        $this->namespace = $this->config['repositories']['namespace'];
        parent::__construct($rawClassName,$module);

    }


    /**
     * Generate common functions of CRUD operations
     * @return void
     */
    function generateCrudMethods()
    {
        //Comment in
        $this->getConstruct()->addComment('TODO: initialize $this->model to your model in order to repository works successfully');
        $this->getConstruct()->addComment('... e.g.:  $this->model = User::class;');

        //Index
        $index = $this->classBuilder->addMethod('all');
        $index->setBody('return $this->model->all();');
        $index->setPublic();

        //Store
        $store = $this->classBuilder->addMethod('create');
        $store->addParameter('data');
        $store->setBody('return $this->model->create($data);');
        $store->setPublic();

        //Show
        $show = $this->classBuilder->addMethod('find');
        $show->addParameter('id');
        $show->setBody('return $this->model->find($id);');
        $show->setPublic();

        //Update
        $update = $this->classBuilder->addMethod('update');
        $update->addParameter('id');
        $update->addParameter('data');
        $update->setBody('return $this->model->where("id",$id)->update($data);');
        $update->setPublic();

        //Destroy
        $destroy = $this->classBuilder->addMethod('delete');
        $destroy->addParameter('id');
        $destroy->setBody('return $this->model->where("id",$id)->delete();');
        $destroy->setPublic();
    }
}
