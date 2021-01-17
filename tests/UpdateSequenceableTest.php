<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Exceptions\SequenceValueOutOfBoundsException;

class UpdateSequenceableTest extends TestCase
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

    /** @test */
    public function it_does_not_need_to_update_the_sequence_when_the_updated_object_receives_a_value_equal_to_its_current_value()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertEquals(2, $secondItem->position);

        $secondItem->update(['position' => 2]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $secondItem->refresh()->position);
        $this->assertEquals(3, $thirdItem->refresh()->position);
    }

    /** @test */
    public function it_throws_an_exception_when_the_object_updated_has_a_sequence_value_that_is_negative()
    {
        $group = Factory::of('Group')->create();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item = Factory::of('Item')->create(['group_id' => $group->id]);

        $item->update(['position' => -1]);
    }

    /** @test */
    public function it_throws_an_exception_when_the_object_updated_has_a_sequence_value_equal_to_zero()
    {
        $group = Factory::of('Group')->create();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item = Factory::of('Item')->create(['group_id' => $group->id]);

        $item->update(['position' => 0]);
    }

    /** @test */
    public function it_throws_an_exception_when_the_object_updated_has_a_sequence_value_greater_than_the_last_value()
    {
        $group = Factory::of('Group')->create();

        $item = Factory::of('Item')->create(['group_id' => $group->id]);
        Factory::of('Item')->create(['group_id' => $group->id]);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item->update(['position' => 3]);
    }

    /** @test */
    public function it_throws_an_exception_when_the_object_updated_has_a_sequence_value_greater_than_the_next_value()
    {
        $group = Factory::of('Group')->create();

        $item = Factory::of('Item')->create(['group_id' => $group->id]);
        Factory::of('Item')->create(['group_id' => $group->id]);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item->update(['position' => 4]);
    }

    /** @test */
    public function it_throws_an_exception_when_the_updated_object_has_a_sequence_value_greater_than_the_next_value_and_is_currently_set_to_null()
    {
        $group = Factory::of('Group')->create();

        $item = Factory::of('Item')->create(['group_id' => $group->id]);
        Factory::of('Item')->create(['group_id' => $group->id]);

        $item->update(['position' => null]);

        $this->assertNull($item->refresh()->position);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item->update(['position' => 4]);
    }

    /** @test */
    public function it_does_not_throw_an_exception_when_the_updated_object_has_a_sequence_value_equal_to_the_next_value_and_is_currently_set_to_null()
    {
        $group = Factory::of('Group')->create();

        Factory::of('Item')->create(['group_id' => $group->id]);
        $item = Factory::of('Item')->create(['group_id' => $group->id]);

        $item->update(['position' => null]);

        $this->assertNull($item->refresh()->position);

        $item->update(['position' => 2]);

        $this->assertEquals(2, $item->refresh()->position);
    }

    /** @test */
    public function it_throws_an_exception_when_the_updated_object_has_a_sequence_value_smaller_than_the_initial_value()
    {
        config(['eloquentsequencer.initial_value' => 10]);

        $group = Factory::of('Group')->create();

        $item = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item->update(['position' => 9]);
    }
}
