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

    public function test_command_creates_single_service_with_modules_enabled_with_opt_module_and_namespace_keyword_must_success(): void
    {
        $serviceName = $this->randomName();
        $moduleName = $this->randomName();
        $this->changeConfig('modules',true);
        $this->changeConfig('services.namespace','App\{module_name}\Services');

        $this->artisan("make:service $serviceName --module=$moduleName")->assertOk();
    }
    public function test_command_creates_single_service_with_modules_enabled_without_opt_module_and_namespace_keyword_must_fail(): void
    {
        $serviceName = $this->randomName();
        $this->changeConfig('modules',true);
        $this->changeConfig('repositories.namespace','App\Services');

        try{
            $this->artisan("make:service $serviceName");
            $this->forceError("This test should have failed.");
        }catch (\Exception $e){
            $this->assertEquals(
                $e->getMessage(),
                'Namespace require a "{module_name}" keyword if module config is enabled.'
            );
        }
    }
    public function test_command_creates_single_service_with_modules_enabled_without_opt_module_must_fail(): void
    {
        $serviceName = $this->randomName();
        $this->changeConfig('modules',true);
        $this->changeConfig('services.namespace','App\{module_name}\Services');

        try{
            $this->artisan("make:service $serviceName");
            $this->forceError("This test should have failed.");
        }catch (\Exception $e){
            $this->assertEquals(
                $e->getMessage(),
                'Module can not be null if module config is enabled.'
            );
        }
    }
}
