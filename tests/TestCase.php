<?php
namespace josanangel\tests;

use Faker\Factory;
use josanangel\ServiceRepositoryManager\ServiceRepositoryManagerServiceProvider;
use Orchestra\Testbench\TestCase as TestCaseOrchestra;

class TestCase extends TestCaseOrchestra

{

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker  = Factory::create();
    }
    protected function getPackageProviders($app)
    {
        return [
            ServiceRepositoryManagerServiceProvider::class,
        ];
    }

    protected function randomName(){
        return 'test_'.time().md5($this->faker->word());
    }

}
