<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

class DeleteSequenceableTest extends TestCase
{
    /** @test */
    public function when_an_element_is_deleted_all_succeeding_siblings_should_be_updated(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $secondItem->delete();

        $this->assertSequenced([
            $firstItem,
            $thirdItem,
        ]);

        $firstItem->delete();

        $this->assertSequenced([$thirdItem]);
    }

    /**
     * @test
     * @group InitialValue
     */
    public function when_all_elements_are_deleted_and_a_new_one_is_created_it_is_assigned_the_value_of_1(): void
    {
        $sequence = $this->createSequence();

        $this->createSequenceable($sequence)
            ->delete();

        $newlyCreatedItem = $this->createSequenceable($sequence);

        $this->assertSequenced([$newlyCreatedItem]);
    }

    /** @test */
    public function the_sequence_is_not_affected_when_an_element_is_deleted_in_another_sequence(): void
    {
        $sequence = $this->createSequence();
        $anotherSequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        $firstItemFromAnotherSequence = $this->createSequenceable($anotherSequence);

        $firstItemFromAnotherSequence->delete();

        $this->assertSequenceableCount($sequence, 2);
        $this->assertSequenced([
            $firstItem,
            $secondItem,
        ]);
    }
}
