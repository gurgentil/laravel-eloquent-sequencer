<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class WithoutSequencingTest extends TestCase
{
    /** @test */
    public function sequencing_may_be_disabled_for_deletes()
    {
        $group = Factory::of('Group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $firstItem->withoutSequencing()
            ->delete();

        $this->assertEquals(2, $secondItem->refresh()->position);
    }

    /** @test */
    public function sequencing_may_be_disabled_for_updates()
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
    public function sequencing_may_be_disabled_for_only_the_following_update()
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
