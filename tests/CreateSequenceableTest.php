<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Gurgentil\LaravelEloquentSequencer\Exceptions\SequenceValueOutOfBoundsException;

class CreateSequenceableTest extends TestCase
{
    /**
     * @test
     * @group InitialValue
     */
    public function it_increments_the_sequence_value_as_elements_are_created(): void
    {
        $sequence = $this->createSequence();

        $this->assertSequenced([
            $this->createSequenceable($sequence),
            $this->createSequenceable($sequence),
            $this->createSequenceable($sequence),
        ]);
    }

    /**
     * @test
     * @group InitialValue
     */
    public function it_starts_the_sequence_at_a_predefined_initial_value(): void
    {
        config(['eloquentsequencer.initial_value' => 10]);

        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $this->assertSequenced([
            $firstItem,
            $secondItem,
            $thirdItem,
        ], 10);
    }

    /** @test */
    public function it_adds_an_element_to_the_start_of_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $newItem = $this->createSequenceable($sequence, 1);

        $this->assertSequenced([
            $newItem,
            $firstItem,
            $secondItem,
            $thirdItem,
        ]);
    }

    /** @test */
    public function it_add_an_element_to_the_middle_of_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $newItem = $this->createSequenceable($sequence, 3);

        $this->assertSequenced([
            $firstItem,
            $secondItem,
            $newItem,
            $thirdItem,
        ]);
    }

    /** @test */
    public function it_adds_an_element_to_the_end_of_the_sequence(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $newItem = $this->createSequenceable($sequence, 4);

        $this->assertSequenced([
            $firstItem,
            $secondItem,
            $thirdItem,
            $newItem,
        ]);
    }

    /** @test */
    public function the_sequence_is_not_affected_by_an_element_being_created_in_another_sequence(): void
    {
        $sequence = $this->createSequence();
        $anotherSequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $firstFromTheOtherSequence = $this->createSequenceable($anotherSequence);
        $secondItem = $this->createSequenceable($sequence);

        $this->assertSequenced([
            $firstItem,
            $secondItem,
        ]);

        $this->assertSequenced([$firstFromTheOtherSequence]);
    }

    /**
     * @test
     * @group SequenceValueOutOfBoundsException
     */
    public function it_throws_an_exception_when_the_element_is_created_with_a_negative_sequence_value(): void
    {
        $sequence = $this->createSequence();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $this->createSequenceable($sequence, -1);
    }

    /**
     * @test
     * @group SequenceValueOutOfBoundsException
     */
    public function it_throws_an_exception_when_the_element_is_created_with_a_sequence_value_equal_to_zero(): void
    {
        $sequence = $this->createSequence();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $this->createSequenceable($sequence, 0);
    }

    /**
     * @test
     * @group SequenceValueOutOfBoundsException
     * @group NextValue
     */
    public function it_throws_an_exception_when_the_element_is_created_with_a_sequence_value_greater_than_the_next_value(): void
    {
        $sequence = $this->createSequence();

        $this->createSequenceable($sequence);
        $this->createSequenceable($sequence);

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $this->createSequenceable($sequence, 4);
    }

    /**
     * @test
     * @group SequenceValueOutOfBoundsException
     * @group InitialValue
     */
    public function it_throws_an_exception_when_the_element_is_created_with_a_sequence_value_smaller_than_the_initial_value(): void
    {
        config(['eloquentsequencer.initial_value' => 10]);

        $sequence = $this->createSequence();

        $this->expectException(SequenceValueOutOfBoundsException::class);

        $this->createSequenceable($sequence, 9);
    }
}
