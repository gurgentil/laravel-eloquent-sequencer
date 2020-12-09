<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;

class WithoutSequencingTest extends TestCase
{
    /** @test */
    public function without_sequencing_works_on_deletes()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $firstItem->withoutSequencing()
            ->delete();

        $this->assertEquals(2, $secondItem->refresh()->position);
    }

    /** @test */
    public function without_sequencing_works_on_updates()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $secondItem->withoutSequencing()
            ->update(['position' => 1]);

        $this->assertEquals(1, $secondItem->refresh()->position);
        $this->assertEquals(1, $firstItem->refresh()->position);
    }

    /** @test */
    public function without_sequencing_should_not_affect_following_operations()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $secondItem->withoutSequencing()
            ->update(['position' => 1]);

        $this->assertEquals(1, $secondItem->refresh()->position);
        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(3, $thirdItem->refresh()->position);

        $secondItem->update(['position' => 2]);
        $thirdItem->update(['position' => 2]);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $thirdItem->refresh()->position);
        $this->assertEquals(3, $secondItem->refresh()->position);
    }
}
