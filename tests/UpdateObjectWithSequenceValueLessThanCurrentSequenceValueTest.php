<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;

class UpdateObjectWithSequenceValueLessThanCurrentSequenceValueTest extends TestCase
{
    /** @test */
    public function it_updates_4th_object_with_sequence_value_equal_to_2_and_move_the_group_around()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fourthItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fifthItem = Factory::of('item')->create(['group_id' => $group->id]);

        $fourthItem->update(['position' => 2]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $fourthItem->refresh()->position);
        $this->assertEquals(3, $secondItem->refresh()->position);
        $this->assertEquals(4, $thirdItem->refresh()->position);
        $this->assertEquals(5, $fifthItem->refresh()->position);
    }

    /** @test */
    public function it_updates_5th_object_with_sequence_value_equal_to_2_and_move_the_group_around()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fourthItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fifthItem = Factory::of('item')->create(['group_id' => $group->id]);

        $fifthItem->update(['position' => 2]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $fifthItem->refresh()->position);
        $this->assertEquals(3, $secondItem->refresh()->position);
        $this->assertEquals(4, $thirdItem->refresh()->position);
        $this->assertEquals(5, $fourthItem->refresh()->position);
    }

    /** @test */
    public function it_updates_3rd_object_with_sequence_value_equal_to_1_and_move_the_group_around()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fourthItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fifthItem = Factory::of('item')->create(['group_id' => $group->id]);

        $thirdItem->update(['position' => 1]);

        $this->assertEquals(1, $thirdItem->refresh()->position);
        $this->assertEquals(2, $firstItem->refresh()->position);
        $this->assertEquals(3, $secondItem->refresh()->position);
        $this->assertEquals(4, $fourthItem->refresh()->position);
        $this->assertEquals(5, $fifthItem->refresh()->position);
    }
}
