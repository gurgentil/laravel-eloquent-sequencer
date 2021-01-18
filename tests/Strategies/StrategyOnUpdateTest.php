<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Strategies;

use Gurgentil\LaravelEloquentSequencer\SequencingStrategy;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class StrategyOnUpdateTest extends TestCase
{
    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_update_enables_sequencing_on_update(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_UPDATE]);

        $secondItem->update(['position' => 1]);

        $this->assertSequenceValue($firstItem, 2);
        $this->assertSequenceValue($secondItem, 1);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_update_disables_sequencing_on_create(): void
    {
        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_UPDATE]);

        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);

        self::assertNull($item->refresh()->position);
    }

    /**
     * @test
     * @group Strategy
     */
    public function strategy_set_to_on_update_disables_sequencing_on_delete(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);

        config(['eloquentsequencer.strategy' => SequencingStrategy::ON_UPDATE]);

        $firstItem->delete();

        $this->assertSequenceValue($secondItem, 2);
    }
}
