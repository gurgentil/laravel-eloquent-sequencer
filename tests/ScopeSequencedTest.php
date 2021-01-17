<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Models\Item;

class ScopeSequencedTest extends TestCase
{
    /** @test */
    public function it_orders_elements_by_sequence_value(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $this->createSequenceable($sequence);

        $secondItem->update(['position' => 1]);
        $firstItem->update(['position' => 3]);

        $items = Item::sequenced()->get();

        self::assertCount(3, $items);
        $this->assertSequenced([
            $items->get(0),
            $items->get(1),
            $items->get(2),
        ]);
    }
}
