<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Strategies;

use Gurgentil\LaravelEloquentSequencer\SequencingStrategy;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class StrategyAlwaysTest extends TestCase
{
    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_always_enables_sequencing_on_create(): void
    {
        config(['eloquentsequencer.strategy' => SequencingStrategy::ALWAYS]);

        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);

        $this->assertSequenceValue($item, 1);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_always_enables_sequencing_on_update(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        config(['eloquentsequencer.strategy' => SequencingStrategy::ALWAYS]);

        $secondItem->update(['position' => 1]);

        $this->assertSequenced([
            $secondItem,
            $firstItem,
        ]);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_always_enables_sequencing_on_delete(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        config(['eloquentsequencer.strategy' => SequencingStrategy::ALWAYS]);

        $firstItem->delete();

        $this->assertSequenceValue($secondItem, 1);
    }
}
