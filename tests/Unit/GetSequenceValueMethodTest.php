<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class GetSequenceValueMethodTest extends TestCase
{
    /** @test */
    public function it_returns_the_sequence_value_of_the_object()
    {
        $group = Factory::of('Group')->create();

        $item = Factory::of('Item')->create(['group_id' => $group->id]);

        $this->assertEquals(1, $item->getSequenceValue());
    }
}
