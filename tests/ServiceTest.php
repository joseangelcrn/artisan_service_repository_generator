<?php


use Faker\Factory;
use Illuminate\Support\Arr;
use josanangel\ServiceRepositoryManager\ServiceRepositoryManagerServiceProvider;
use Orchestra\Testbench\TestCase;
use Traits\HasPackageProvider;

class ServiceTest extends TestCase
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


    public function test_command_creates_single_service_successfully(): void
    {
        $serviceName =  $this->randomName();

        $this->artisan("make:service $serviceName")->assertOk();
    }

    public function test_command_create_single_service_with_multiple_repositories_as_dependencies(): void
    {
        $serviceName =  $this->randomName();

        $nRepositories = rand(1,4);
        $repositoryNames = [];

        for ($i = 0; $i < $nRepositories; $i++) {
            $repositoryNames[] =  $this->randomName();
        }

        $repositoryNames = Arr::join($repositoryNames,',');

        $this->artisan("make:service $serviceName --repositories=$repositoryNames")->assertOk();
    }

    public function test_command_create_service_with_multiple_repositories_and_services_as_dependencies(): void
    {
        $serviceName =  $this->randomName();

        $nRepositories = rand(1,4);
        $repositoryNames = [];

        for ($i = 0; $i < $nRepositories; $i++) {
            $repositoryNames[] =  $this->randomName();
        }

        $repositoryNames = Arr::join($repositoryNames,',');


        $nServices = rand(1,4);
        $serviceNames = [];

        for ($i = 0; $i < $nServices; $i++) {
            $serviceNames[] =  $this->randomName();
        }
        $serviceNames = Arr::join($serviceNames,',');

        $this->artisan("make:service $serviceName --repositories=$repositoryNames --services=$serviceNames")->assertOk();

    }
}
