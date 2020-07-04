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
}
