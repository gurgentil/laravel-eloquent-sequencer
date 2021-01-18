<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

class WithoutSequencingTest extends TestCase
{
    /** @test */
    public function without_sequencing_works_on_deletes(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        $firstItem->withoutSequencing()
            ->delete();

        $this->assertSequenceValue($secondItem, 2);
    }

    /** @test */
    public function without_sequencing_works_on_updates(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        $secondItem->withoutSequencing()
            ->update(['position' => 1]);

        $this->assertSequenceValue($secondItem, 1);
        $this->assertSequenceValue($firstItem, 1);
    }

    /** @test */
    public function without_sequencing_disables_sequencing_only_for_the_current_operation(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $secondItem->withoutSequencing()
            ->update(['position' => 1]);

        $this->assertSequenceValue($secondItem, 1);
        $this->assertSequenceValue($firstItem, 1);
        $this->assertSequenceValue($thirdItem, 3);

        $secondItem->update(['position' => 2]);
        $thirdItem->update(['position' => 2]);

        $this->assertSequenceValue($firstItem, 1);
        $this->assertSequenceValue($thirdItem, 2);
        $this->assertSequenceValue($secondItem, 3);
    }
}
