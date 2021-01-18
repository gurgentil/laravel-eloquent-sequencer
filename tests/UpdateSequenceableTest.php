<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Gurgentil\LaravelEloquentSequencer\Exceptions\SequenceValueOutOfBoundsException;

class UpdateSequenceableTest extends TestCase
{
    /** @test */
    public function it_moves_an_element_towards_the_start_of_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);
        $fourthItem = $this->createSequenceable($sequence);
        $fifthItem = $this->createSequenceable($sequence);

        $fourthItem->update(['position' => 2]);

        $this->assertSequenced([
            $firstItem,
            $fourthItem,
            $secondItem,
            $thirdItem,
            $fifthItem,
        ]);
    }

    /** @test */
    public function it_moves_the_last_element_towards_the_start_of_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);
        $fourthItem = $this->createSequenceable($sequence);
        $fifthItem = $this->createSequenceable($sequence);

        $fifthItem->update(['position' => 2]);

        $this->assertSequenced([
            $firstItem,
            $fifthItem,
            $secondItem,
            $thirdItem,
            $fourthItem,
        ]);
    }

    /** @test */
    public function it_moves_an_element_to_the_first_position_in_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);
        $fourthItem = $this->createSequenceable($sequence);
        $fifthItem = $this->createSequenceable($sequence);

        $thirdItem->update(['position' => 1]);

        $this->assertSequenced([
            $thirdItem,
            $firstItem,
            $secondItem,
            $fourthItem,
            $fifthItem,
        ]);
    }

    /** @test */
    public function it_moves_an_element_towards_the_end_of_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);
        $fourthItem = $this->createSequenceable($sequence);
        $fifthItem = $this->createSequenceable($sequence);

        $secondItem->update(['position' => 4]);

        $this->assertSequenced([
            $firstItem,
            $thirdItem,
            $fourthItem,
            $secondItem,
            $fifthItem,
        ]);
    }

    /** @test */
    public function it_moves_the_first_element_towards_the_end_of_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);
        $fourthItem = $this->createSequenceable($sequence);
        $fifthItem = $this->createSequenceable($sequence);

        $firstItem->update(['position' => 4]);

        $this->assertSequenced([
            $secondItem,
            $thirdItem,
            $fourthItem,
            $firstItem,
            $fifthItem,
        ]);
    }

    /** @test */
    public function it_moves_an_element_to_the_last_position_in_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);
        $fourthItem = $this->createSequenceable($sequence);
        $fifthItem = $this->createSequenceable($sequence);

        $secondItem->update(['position' => 5]);

        $this->assertSequenced([
            $firstItem,
            $thirdItem,
            $fourthItem,
            $fifthItem,
            $secondItem,
        ]);
    }

    /** @test */
    public function it_does_not_change_the_sequence_when_the_updated_element_gets_a_value_equal_to_its_current_value(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $secondItem->update(['position' => 2]);

        $this->assertSequenced([
            $firstItem,
            $secondItem,
            $thirdItem,
        ]);
    }

    /**
     * @test
     * @group InitialValue
     */
    public function when_a_sequence_is_updated_the_first_element_will_receive_the_predefined_initial_value(): void
    {
        config(['eloquentsequencer.initial_value' => 10]);

        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $firstItem->update(['position' => 12]);

        $this->assertSequenced([
            $secondItem,
            $thirdItem,
            $firstItem,
        ], 10);
    }

    /**
     * @test
     * @group SequenceValueOutOfBoundsException
     */
    public function it_throws_an_exception_when_the_updated_element_has_a_negative_sequence_value(): void
    {
        $sequence = $this->createSequence();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item = $this->createSequenceable($sequence);

        $item->update(['position' => -1]);
    }

    /**
     * @test
     * @group SequenceValueOutOfBoundsException
     */
    public function it_throws_an_exception_when_the_updated_element_has_a_sequence_value_equal_to_zero(): void
    {
        $sequence = $this->createSequence();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item = $this->createSequenceable($sequence);

        $item->update(['position' => 0]);
    }

    /**
     * @test
     * @group LastValue
     * @group SequenceValueOutOfBoundsException
     */
    public function it_throws_an_exception_when_the_updated_element_has_a_sequence_value_greater_than_the_last_value(): void
    {
        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);
        $this->createSequenceable($sequence);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item->update(['position' => 3]);
    }

    /**
     * @test
     * @group NextValue
     * @group SequenceValueOutOfBoundsException
     */
    public function it_throws_an_exception_when_the_updated_element_has_a_sequence_value_greater_than_the_next_value(): void
    {
        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);
        $this->createSequenceable($sequence);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item->update(['position' => 4]);
    }

    /**
     * @test
     * @group NextValue
     * @group SequenceValueOutOfBoundsException
     */
    public function it_throws_an_exception_when_the_updated_element_has_a_sequence_value_greater_than_the_next_value_and_is_currently_set_to_null(): void
    {
        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);
        $this->createSequenceable($sequence);

        $item->update(['position' => null]);

        self::assertNull($item->refresh()->position);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item->update(['position' => 4]);
    }

    /**
     * @test
     * @group NextValue
     * @group SequenceValueOutOfBoundsException
     */
    public function it_does_not_throw_an_exception_when_the_updated_element_has_a_sequence_value_equal_to_the_next_value_and_is_currently_set_to_null(): void
    {
        $sequence = $this->createSequence();

        $this->createSequenceable($sequence);
        $item = $this->createSequenceable($sequence);

        $item->update(['position' => null]);

        self::assertNull($item->position);

        $item->update(['position' => 2]);

        $this->assertSequenceValue($item, 2);
    }

    /**
     * @test
     * @group SequenceValueOutOfBoundsException
     * @group InitialValue
     */
    public function it_throws_an_exception_when_the_updated_element_has_a_sequence_value_smaller_than_the_initial_value(): void
    {
        config(['eloquentsequencer.initial_value' => 10]);

        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $item->update(['position' => 9]);
    }
}
