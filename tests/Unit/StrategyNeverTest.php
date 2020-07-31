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

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_never_works_on_update()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('item')->create(['group_id' => $group->id]);

        config(['eloquentsequencer.strategy' => SequencingStrategy::NEVER]);

        $secondItem->update(['position' => 1]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(1, $secondItem->refresh()->position);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_never_works_on_delete()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);

        config(['eloquentsequencer.strategy' => SequencingStrategy::NEVER]);

        $firstItem->delete();

        $this->assertEquals(2, $secondItem->refresh()->position);
    }
}
