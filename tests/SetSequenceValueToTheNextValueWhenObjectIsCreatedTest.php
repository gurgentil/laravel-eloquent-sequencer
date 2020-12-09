<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;

class SetSequenceValueToTheNextValueWhenObjectIsCreatedTest extends TestCase
{
    /** @test */
    public function it_assigns_a_sequence_value_to_an_object_upon_creation()
    {
        $group = Factory::of('Group')->create();

        $item = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertNotNull($item->position);
    }

    /** @test */
    public function it_assigns_1_to_the_1st_object()
    {
        $group = Factory::of('Group')->create();

        $item = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertEquals(1, $item->position);
    }

    /** @test */
    public function it_assigns_10_to_the_10th_object()
    {
        $group = Factory::of('Group')->create();

        $items = Factory::of('Item')
            ->times(10)
            ->create(['group_id' => $group->id]);

        $this->assertEquals(10, $items->last()->position);
    }

    /** @test */
    public function the_sequence_is_not_affected_by_a_new_object_created_in_another_group()
    {
        $group = Factory::of('Group')->create();

        $anotherGroup = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $firstFromTheOtherGroup = Factory::of('Item')->create(['group_id' => $anotherGroup->id]);

        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertEquals(1, $firstFromTheOtherGroup->refresh()->position);
        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $secondItem->refresh()->position);
    }
}
