<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Models\Item;

class SequenceableTest extends TestCase
{
    /** @test */
    public function the_column_name_can_be_set_in_the_configuration_file(): void
    {
        config(['eloquentsequencer.column_name' => 'order']);

        self::assertEquals('order', Item::getSequenceColumnName());
    }

    /** @test */
    public function it_gets_the_sequence_value(): void
    {
        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);

        self::assertEquals(1, $item->getSequenceValue());
    }
}
