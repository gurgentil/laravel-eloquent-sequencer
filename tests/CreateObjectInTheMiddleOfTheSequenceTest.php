<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;

class CreateObjectInTheMiddleOfTheSequenceTest extends TestCase
{
    /** @test */
    public function it_creates_an_object_with_sequence_value_equal_to_1_and_move_the_group_around()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $newItem = Factory::of('Item')->create([
            'position' => 1,
            'group_id' => $group->id,
        ]);

        $this->assertEquals(1, $newItem->refresh()->position);
        $this->assertEquals(2, $firstItem->refresh()->position);
        $this->assertEquals(3, $secondItem->refresh()->position);
        $this->assertEquals(4, $thirdItem->refresh()->position);
    }

    /** @test */
    public function it_creates_an_object_with_sequence_value_equal_to_2_and_move_the_group_around()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $newItem = Factory::of('Item')->create([
            'position' => 2,
            'group_id' => $group->id,
        ]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $newItem->refresh()->position);
        $this->assertEquals(3, $secondItem->refresh()->position);
        $this->assertEquals(4, $thirdItem->refresh()->position);
    }

    /** @test */
    public function it_creates_an_object_with_sequence_value_equal_to_3_and_move_the_group_around()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $newItem = Factory::of('Item')->create([
            'position' => 3,
            'group_id' => $group->id,
        ]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $secondItem->refresh()->position);
        $this->assertEquals(3, $newItem->refresh()->position);
        $this->assertEquals(4, $thirdItem->refresh()->position);
    }

    /** @test */
    public function it_creates_an_object_with_sequence_value_equal_to_4_and_move_the_group_around()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $newItem = Factory::of('Item')->create([
            'position' => 4,
            'group_id' => $group->id,
        ]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $secondItem->refresh()->position);
        $this->assertEquals(3, $thirdItem->refresh()->position);
        $this->assertEquals(4, $newItem->refresh()->position);
    }
}
