<?php

namespace josanangel\tests;


class RepositoryTest extends TestCase
{

    public function test_command_creates_single_repository_successfully(): void
    {
        $repositoryName = $this->randomName();

        $this->artisan("make:repository $repositoryName")->assertOk();
    }
    public function test_command_creates_single_repository_with_modules_enabled_with_opt_module_and_namespace_keyword_must_success(): void
    {
        $repositoryName = $this->randomName();
        $moduleName = $this->randomName();
        $this->changeConfig('modules',true);
        $this->changeConfig('repositories.namespace','App\{module_name}\Repositories');

        $this->artisan("make:repository $repositoryName --module=$moduleName")->assertOk();
    }
    public function test_command_creates_single_repository_with_modules_enabled_without_opt_module_and_namespace_keyword_must_fail(): void
    {
        $repositoryName = $this->randomName();
        $this->changeConfig('modules',true);
        $this->changeConfig('repositories.namespace','App\Repositories');

        try{
            $this->artisan("make:repository $repositoryName");
            $this->forceError("This test should have failed.");
        }catch (\Exception $e){
            $this->assertEquals(
                $e->getMessage(),
                'Namespace require a "{module_name}" keyword if module config is enabled.'
            );
        }
    }
    public function test_command_creates_single_repository_with_modules_enabled_without_opt_module_must_fail(): void
    {
        $repositoryName = $this->randomName();
        $this->changeConfig('modules',true);
        $this->changeConfig('repositories.namespace','App\{module_name}\Repositories');

        try{
            $this->artisan("make:repository $repositoryName");
            $this->forceError("This test should have failed.");
        }catch (\Exception $e){
            $this->assertEquals(
                $e->getMessage(),
                'Module can not be null if module config is enabled.'
            );
        }
    }
}
