<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Models\Item;

class ScopeSequencedTest extends TestCase
{
    /** @test */
    public function it_orders_records_by_sequence_value()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $secondItem->update(['position' => 1]);
        $firstItem->update(['position' => 3]);

        $items = Item::sequenced()->get();

        $this->assertCount(3, $items);

        $this->assertEquals(2, $items->get(0)->id);
        $this->assertEquals(3, $items->get(1)->id);
        $this->assertEquals(1, $items->get(2)->id);
    }
}
