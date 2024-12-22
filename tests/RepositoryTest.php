<?php

namespace josanangel\tests;

use Faker\Factory;
use josanangel\ServiceRepositoryManager\ServiceRepositoryManagerServiceProvider;
use Orchestra\Testbench\TestCase;
use Traits\HasPackageProvider;

class RepositoryTest extends TestCase
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

    public function test_command_creates_single_repository_successfully(): void
    {
        $factory = Factory::create();
        $repositoryName =  $factory->word();

        $this->artisan("make:repository $repositoryName")->assertOk();
    }
}
