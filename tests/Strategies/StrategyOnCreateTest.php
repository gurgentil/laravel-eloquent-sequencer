<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Strategies;

use Gurgentil\LaravelEloquentSequencer\SequencingStrategy;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class StrategyOnCreateTest extends TestCase
{
    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_create_enables_sequencing_on_create(): void
    {
        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_CREATE]);

        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);

        $this->assertSequenceValue($item, 1);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_create_disables_sequencing_on_update(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_CREATE]);

        $secondItem->update(['position' => 1]);

        $this->assertSequenceValue($firstItem, 1);
        $this->assertSequenceValue($secondItem, 1);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_create_disables_sequencing_on_delete(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_CREATE]);

        $firstItem->delete();

        $this->assertSequenceValue($secondItem, 2);
    }
}
