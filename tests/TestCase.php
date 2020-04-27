<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Gurgentil\LaravelEloquentSequencer\LaravelEloquentSequencerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelEloquentSequencerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->getTempDirectory() . '/database.sqlite',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase()
    {
        $this->resetDatabase();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function resetDatabase()
    {
        file_put_contents($this->getTempDirectory() . '/database.sqlite', null);
    }

    protected function getTempDirectory(): string
    {
        return __DIR__ . '/temp';
    }
}
