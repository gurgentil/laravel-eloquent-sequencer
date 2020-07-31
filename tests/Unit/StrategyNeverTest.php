<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\SequencingStrategy;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class StrategyNeverTest extends TestCase
{
    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_never_works_on_create()
    {
        config(['eloquentsequencer.strategy' => SequencingStrategy::NEVER]);

        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertNull($firstItem->refresh()->position);
    }
}