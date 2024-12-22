<?php


use Faker\Factory;
use Illuminate\Support\Arr;
use josanangel\ServiceRepositoryManager\ServiceRepositoryManagerServiceProvider;
use Orchestra\Testbench\TestCase;
use Traits\HasPackageProvider;

class ServiceTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceRepositoryManagerServiceProvider::class,
        ];
    }
    protected function setUp(): void
    {
        parent::setUp();
    }


    public function test_command_creates_single_service_successfully(): void
    {
        $factory = Factory::create();
        $serviceName =  $factory->word();

        $this->artisan("make:service $serviceName")->assertOk();
    }

    public function test_command_create_single_service_with_multiple_repositories_as_dependencies(): void
    {
        $factory = Factory::create();
        $serviceName =  $factory->word();

        $nRepositories = rand(1,4);
        $repositoryNames = [];

        for ($i = 0; $i < $nRepositories; $i++) {
            $repositoryNames[] = $factory->word();
        }

        $repositoryNames = Arr::join($repositoryNames,',');

        $this->artisan("make:service $serviceName --repositories=$repositoryNames")->assertOk();
    }

    public function test_command_create_service_with_multiple_repositories_and_services_as_dependencies(): void
    {
        $factory = Factory::create();
        $serviceName =  $factory->word();

        $nRepositories = rand(1,4);
        $repositoryNames = [];

        for ($i = 0; $i < $nRepositories; $i++) {
            $repositoryNames[] = $factory->word();
        }

        $repositoryNames = Arr::join($repositoryNames,',');


        $nServices = rand(1,4);
        $serviceNames = [];

        for ($i = 0; $i < $nServices; $i++) {
            $serviceNames[] = $factory->word();
        }
        $serviceNames = Arr::join($serviceNames,',');

        $this->artisan("make:service $serviceName --repositories=$repositoryNames --services=$serviceNames")->assertOk();

    }
}
