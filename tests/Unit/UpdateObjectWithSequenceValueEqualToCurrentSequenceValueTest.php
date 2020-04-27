<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class UpdateObjectWithSequenceValueEqualToCurrentSequenceValueTest extends TestCase
{
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
}
