<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Gurgentil\LaravelEloquentSequencer\Exceptions\SequenceValueOutOfBoundsException;
use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class UpdateObjectWithSequenceValueOutOfBoundsText extends TestCase
{
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

        $item->update(['position' => 3]);
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
