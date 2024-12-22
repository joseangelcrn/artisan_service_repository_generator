<?php

namespace josanangel\tests;

use Faker\Factory;
use josanangel\ServiceRepositoryManager\ServiceRepositoryManagerServiceProvider;
use Orchestra\Testbench\TestCase;
use Traits\HasPackageProvider;

class RepositoryTest extends TestCase
{
    protected  $faker;

    protected function randomName(){
        return 'test_'.time().md5($this->faker->word());
    }
    protected function getPackageProviders($app)
    {
        return [
            ServiceRepositoryManagerServiceProvider::class,
        ];
    }
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker  = Factory::create();
    }


    public function test_command_creates_single_repository_successfully(): void
    {
        $repositoryName = $this->randomName();

        $this->artisan("make:repository $repositoryName")->assertOk();
    }
}
