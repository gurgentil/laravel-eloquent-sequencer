<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class DeleteObjectWithoutSequencingTest extends TestCase
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
}
