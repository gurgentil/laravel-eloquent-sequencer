<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\Models\Item;

class SequenceWithNoGroupTest extends TestCase
{
    /** @test */
    public function test()
    {
        $group = Factory::of('Group')->create();

        Item::$sequenceableKeys = [];

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertEquals(1, $firstItem->position);
        $this->assertEquals(2, $secondItem->position);
        $this->assertEquals(3, $thirdItem->position);

        $thirdItem->update(['position' => 2]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $thirdItem->refresh()->position);
        $this->assertEquals(3, $secondItem->refresh()->position);

        Item::$sequenceableKeys = ['group_id'];
    }
}
