<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Gurgentil\LaravelEloquentSequencer\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
