<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Commands;

use Exception;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class FlushSequenceValuesCommandTest extends TestCase
{
    /** @test */
    public function the_flush_command_requires_a_valid_model_name(): void
    {
        $this->expectException(Exception::class);

        $this->artisan('sequence:flush \\\App\\\InvalidModel')
            ->expectsOutput('Model `\\App\\InvalidModel` not found.')
            ->assertExitCode(1);
    }

    /** @test */
    public function the_flush_command_does_not_proceed_when_the_model_count_is_0(): void
    {
        $this->artisan('sequence:flush \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Nothing to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_flush_command_does_not_update_values_that_are_already_null(): void
    {
        $sequence = $this->createSequence();

        $this->createSequenceable($sequence)
            ->update(['position' => null]);

        $this->artisan('sequence:flush \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Analyzing and flushing sequence values from 1 object(s).')
            ->expectsOutput('0 row(s) were updated.')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_flush_command_flushes_sequence_values(): void
    {
        $sequence = $this->createSequence();

        $firstItem = $this->createSequenceable($sequence);
        $secondItem = $this->createSequenceable($sequence);
        $thirdItem = $this->createSequenceable($sequence);

        $secondItem->update(['position' => null]);
        $thirdItem->update(['position' => null]);

        self::assertNotNull($firstItem->position);
        self::assertNull($secondItem->position);
        self::assertNull($thirdItem->position);

        $this->artisan('sequence:flush \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Analyzing and flushing sequence values from 3 object(s).')
            ->expectsOutPut('1 row(s) were updated.')
            ->assertExitCode(0);

        self::assertNull($firstItem->refresh()->position);
        self::assertNull($secondItem->refresh()->position);
        self::assertNull($thirdItem->refresh()->position);
    }
}
