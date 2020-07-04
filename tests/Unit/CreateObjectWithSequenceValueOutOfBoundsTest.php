<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Gurgentil\LaravelEloquentSequencer\Exceptions\SequenceValueOutOfBoundsException;
use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class CreateObjectWithSequenceValueOutOfBoundsTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_the_object_created_has_a_sequence_value_that_is_negative()
    {
        $group = Factory::of('Group')->create();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        Factory::of('Item')->create([
            'position' => -1,
            'group_id' => $group->id,
        ]);
    }

    /** @test */
    public function it_throws_an_exception_when_the_object_created_has_a_sequence_value_equal_to_zero()
    {
        $group = Factory::of('Group')->create();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        Factory::of('Item')->create([
            'position' => 0,
            'group_id' => $group->id,
        ]);
    }

    /** @test */
    public function it_throws_an_exception_when_the_object_created_has_a_sequence_value_greater_than_the_next_value()
    {
        $group = Factory::of('Group')->create();

        Factory::of('Item')->create(['group_id' => $group->id]);
        Factory::of('Item')->create(['group_id' => $group->id]);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        Factory::of('Item')->create([
            'position' => 4,
            'group_id' => $group->id,
        ]);
    }

    /** @test */
    public function it_throws_an_exception_when_the_object_created_has_a_sequence_value_smaller_than_the_initial_value()
    {
        config(['eloquentsequencer.initial_vaue' => 10]);

        $group = Factory::of('Group')->create();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        Factory::of('Item')->create([
            'position' => 9,
            'group_id' => $group->id,
        ]);
    }
}
