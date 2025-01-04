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
        $this->loadConfiguration();
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

    protected function loadConfiguration()
    {
        $globalConfPath  = __DIR__.'/../config/global.php';
        $this->app->make('config')->set('service_repository_manager',include  $globalConfPath);
    }

    protected function changeConfig($key,$value)
    {
        config()->set("service_repository_manager.$key",$value);
    }

    protected function forceError($message)
    {
        $this->assertEquals(true,false,$message);
    }
}
