<?php

namespace josanangel\tests;

use Illuminate\Support\Arr;


class ServiceTest extends TestCase
{

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

    public function test_command_create_single_service_with_multiple_services_as_dependencies(): void
    {
        $serviceName =  $this->randomName();

        $nServices = rand(1,4);
        $serviceNames = [];

        for ($i = 0; $i < $nServices; $i++) {
            $serviceNames[] =  $this->randomName();
        }
        $serviceNames = Arr::join($serviceNames,',');

        $this->artisan("make:service $serviceName --services=$serviceNames")->assertOk();
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
