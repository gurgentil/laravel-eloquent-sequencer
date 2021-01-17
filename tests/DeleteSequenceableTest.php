<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;

class DeleteSequenceableTest extends TestCase
{
    /** @test */
    public function when_an_element_is_deleted_all_succeeding_siblings_should_be_updated(): void
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $secondItem->delete();

        self::assertEquals(1, $firstItem->refresh()->position);
        self::assertEquals(2, $thirdItem->refresh()->position);

        $firstItem->delete();

        self::assertEquals(1, $thirdItem->refresh()->position);
    }

    /** @test */
    public function when_all_elements_are_deleted_and_a_new_one_is_created_it_is_assigned_the_value_of_1(): void
    {
        $group = Factory::of('Group')->create();

        $deletedItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $deletedItem->delete();

        $this->assertCount(0, $group->refresh()->items);

        $newItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertEquals(1, $newItem->position);
    }

    /** @test */
    public function the_sequence_group_is_not_affected_when_an_element_is_deleted_in_another_sequence(): void
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
