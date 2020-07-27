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

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        static::resetDatabase();
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
            'database' => static::getTempDirectory().'/database.sqlite',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase()
    {
        static::resetDatabase();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected static function resetDatabase()
    {
        file_put_contents(static::getTempDirectory().'/database.sqlite', null);
    }

    protected static function getTempDirectory(): string
    {
        return __DIR__.'/temp';
    }
}
