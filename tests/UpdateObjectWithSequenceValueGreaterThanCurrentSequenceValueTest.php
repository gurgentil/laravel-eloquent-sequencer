<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;

class UpdateObjectWithSequenceValueGreaterThanCurrentSequenceValueTest extends TestCase
{
    /** @test */
    public function it_updates_2nd_object_with_sequence_value_equal_to_4_and_move_the_group_around()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fourthItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fifthItem = Factory::of('item')->create(['group_id' => $group->id]);

        $secondItem->update(['position' => 4]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $thirdItem->refresh()->position);
        $this->assertEquals(3, $fourthItem->refresh()->position);
        $this->assertEquals(4, $secondItem->refresh()->position);
        $this->assertEquals(5, $fifthItem->refresh()->position);
    }

    /** @test */
    public function it_updates_1st_object_with_sequence_value_equal_to_4_and_move_the_group_around()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fourthItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fifthItem = Factory::of('item')->create(['group_id' => $group->id]);

        $firstItem->update(['position' => 4]);

        $this->assertEquals(1, $secondItem->refresh()->position);
        $this->assertEquals(2, $thirdItem->refresh()->position);
        $this->assertEquals(3, $fourthItem->refresh()->position);
        $this->assertEquals(4, $firstItem->refresh()->position);
        $this->assertEquals(5, $fifthItem->refresh()->position);
    }

    /** @test */
    public function it_updates_2nd_object_with_sequence_value_equal_to_5_and_move_the_group_around()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fourthItem = Factory::of('item')->create(['group_id' => $group->id]);
        $fifthItem = Factory::of('item')->create(['group_id' => $group->id]);

        $secondItem->update(['position' => 5]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $thirdItem->refresh()->position);
        $this->assertEquals(3, $fourthItem->refresh()->position);
        $this->assertEquals(4, $fifthItem->refresh()->position);
        $this->assertEquals(5, $secondItem->refresh()->position);
    }
}
