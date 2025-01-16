<?php

namespace josanangel\tests;


class RepositoryCommandTest extends TestCase
{

    public function test_command_creates_single_repository_successfully(): void
    {
        $repositoryName = $this->randomName();

        $this->artisan("make:repository $repositoryName")->assertOk();
    }

    /**
     * If command is executed with modules conf enabled and --module option is present in command calling and SRM_REPOSITORIES_NAMESPACE env value
     * contains the {node_module} keyword should be successfully run
     * @return void
     */
    public function test_command_creates_single_repository_with_modules_enabled_with_opt_module_and_namespace_keyword_must_success(): void
    {
        $repositoryName = $this->randomName();
        $moduleName = $this->randomName();
        $this->changeConfig('modules',true);
        $this->changeConfig('repositories.namespace','App\{module_name}\Repositories');

        $this->artisan("make:repository $repositoryName --module=$moduleName")->assertOk();
    }

    /**
     * If command is executed with modules conf enabled and --module option is not present in command calling and SRM_REPOSITORIES_NAMESPACE env value
     * does not contain the {node_module} keyword should to throw the specific error
     * @return void
     */
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

    /**
     * If command is executed with modules enabled and --module option is not present but SRM_SERVICES_NAMESPACE env value
     * contains {node_module} should throw the specific error
     * @return void
     */
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

    /**
     * If command is executed with --crud option must be successfully executed.
     * @return void
     */
    public function test_command_creates_single_repository_with_modules_disabled_with_opt_crud_must_success(): void
    {
        $repositoryName = $this->randomName();
        $this->artisan("make:repository $repositoryName --crud")->assertOk();
    }
}
