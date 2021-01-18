<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Gurgentil\LaravelEloquentSequencer\Tests\Models\Item;

class OneSequencePerModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Item::$sequenceableKeys = [];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Item::$sequenceableKeys = ['group_id'];
    }

    /** @test */
    public function it_sees_the_whole_table_as_one_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $this->assertSequenced([
            $firstItem,
            $secondItem,
            $thirdItem,
        ]);

        $thirdItem->update(['position' => 2]);

        $this->assertSequenced([
            $firstItem,
            $thirdItem,
            $secondItem,
        ]);
    }
}
