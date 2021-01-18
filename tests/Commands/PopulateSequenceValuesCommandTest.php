<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Commands;

use Exception;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class PopulateSequenceValuesCommandTest extends TestCase
{
    /** @test */
    public function the_populate_command_requires_a_valid_model_name(): void
    {
        $this->expectException(Exception::class);

        $this->artisan('sequence:populate \\\App\\\InvalidModel')
            ->expectsOutput('Model `\\App\\InvalidModel` not found.')
            ->assertExitCode(1);
    }

    /** @test */
    public function the_populate_command_does_not_proceed_when_the_model_count_is_0(): void
    {
        $this->artisan('sequence:populate \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Nothing to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_populate_command_does_not_update_values_that_are_not_null(): void
    {
        $sequence = $this->createSequence();

        $item = $this->createSequenceable($sequence);

        self::assertNotNull($item->position);

        $this->artisan('sequence:populate \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Analyzing and populating sequence values in 1 object(s).')
            ->expectsOutput('0 row(s) were updated.')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_populate_command_populates_every_empty_sequence_value(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $secondItem->update(['position' => null]);
        $thirdItem->update(['position' => null]);

        $this->assertSequenceValue($firstItem, 1);
        self::assertNull($secondItem->position);
        self::assertNull($thirdItem->position);

        $this->artisan('sequence:populate \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Analyzing and populating sequence values in 3 object(s).')
            ->expectsOutPut('2 row(s) were updated.')
            ->assertExitCode(0);

        $this->assertSequenceValue($firstItem, 1);
        $this->assertSequenceValue($secondItem, 2);
        $this->assertSequenceValue($thirdItem, 3);
    }
}
