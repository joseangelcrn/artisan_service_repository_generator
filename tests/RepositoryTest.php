<?php

namespace josanangel\tests;


class RepositoryTest extends TestCase
{

    public function test_command_creates_single_repository_successfully(): void
    {
        $repositoryName = $this->randomName();

        $this->artisan("make:repository $repositoryName")->assertOk();
    }
}
