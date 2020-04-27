<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class RearrangeSequenceWhenAnObjectIsDeletedTest extends TestCase
{
    /** @test */
    public function it_assigns_1_to_the_second_object_and_two_to_the_third_one_when_the_first_one_is_deleted()
    {
        $group = Factory::of('Group')->create();
        
        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);
        
        $firstItem->delete();

        $this->assertEquals(1, $secondItem->refresh()->position);
        $this->assertEquals(2, $thirdItem->refresh()->position);
    }

    /** @test */
    public function it_assigns_2_to_third_object_when_the_second_one_is_deleted()
    {
        $group = Factory::of('Group')->create();
        
        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);
        
        $secondItem->delete();

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $thirdItem->refresh()->position);
    }

    /** @test */
    public function when_all_objects_are_deleted_and_a_new_one_is_created_it_is_assigned_the_value_of_1()
    {
        $group = Factory::of('Group')->create();
        
        $deletedItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $deletedItem->delete();

        $this->assertCount(0, $group->refresh()->items);

        $newItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertEquals(1, $newItem->position);
    }

    /** @test */
    public function the_sequence_group_is_not_affected_when_an_object_is_deleted_in_another_group()
    {
        $group = Factory::of('Group')->create();
        $anotherGroup = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $firstFromTheOtherGroup = Factory::of('Item')->create(['group_id' => $anotherGroup->id]);

        $firstFromTheOtherGroup->delete();

        $this->assertCount(2, $group->refresh()->items);
        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $secondItem->refresh()->position);
    }
}
