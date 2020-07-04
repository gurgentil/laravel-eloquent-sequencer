<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class InitialSequenceValueTest extends TestCase
{
    /** @test */
    public function it_starts_a_sequence_at_a_predefined_value()
    {
        config(['eloquentsequencer.initial_value' => 10]);

        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertEquals(10, $firstItem->position);
        $this->assertEquals(11, $secondItem->position);
        $this->assertEquals(12, $thirdItem->position);
    }

    /** @test */
    public function when_a_sequence_is_updated_the_first_object_will_have_the_initial_value()
    {
        config(['eloquentsequencer.initial_value' => 10]);

        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $firstItem->update(['position' => 12]);

        $this->assertEquals(10, $secondItem->refresh()->position);
        $this->assertEquals(11, $thirdItem->refresh()->position);
        $this->assertEquals(12, $firstItem->refresh()->position);
    }
}
