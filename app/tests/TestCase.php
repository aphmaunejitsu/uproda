<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(database_path('migrations/testing'));
    }
}
