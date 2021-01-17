<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\ServiceProvider;
use Gurgentil\LaravelEloquentSequencer\Tests\Models\Group;
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

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function createSequenceable(Group $sequence, ?int $position = null)
    {
        $attributes = ['group_id' => $sequence->id];

        if (!is_null($position)) {
            $attributes['position'] = $position;
        }

        return Factory::of('Item')->create($attributes);
    }

    protected function createSequence()
    {
        return Factory::of('Group')->create();
    }

    protected function assertSequenced(array $sequenceables): self
    {
        collect($sequenceables)->each(function ($sequenceable, $index) {
            $expectedPosition = $index + 1;

            self::assertEquals($expectedPosition, $sequenceable->refresh()->position);
        });

        return $this;
    }

    protected function assertSequenceableCount(Group $sequence, int $expectedCount): self
    {
        self::assertCount($expectedCount, $sequence->refresh()->items);

        return $this;
    }
}
