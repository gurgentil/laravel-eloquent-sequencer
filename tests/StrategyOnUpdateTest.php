<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\SequencingStrategy;

class StrategyOnUpdateTest extends TestCase
{
    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_update_enables_sequencing_on_update()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('item')->create(['group_id' => $group->id]);

        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_UPDATE]);

        $secondItem->update(['position' => 1]);

        $this->assertEquals(2, $firstItem->refresh()->position);
        $this->assertEquals(1, $secondItem->refresh()->position);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_update_disables_sequencing_on_create()
    {
        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_UPDATE]);

        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertNull($firstItem->refresh()->position);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_update_disables_sequencing_on_delete()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);

        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_UPDATE]);

        $firstItem->delete();

        $this->assertEquals(2, $secondItem->refresh()->position);
    }
}
