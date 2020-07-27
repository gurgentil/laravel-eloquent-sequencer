<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Exception;
use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class FlushSequenceValuesCommandTest extends TestCase
{
    /** @test */
    public function the_flush_command_requires_a_valid_model_name()
    {
        $this->expectException(Exception::class);

        $this->artisan('sequence:flush \\\App\\\InvalidModel')
            ->expectsOutput('Class `\\App\\InvalidModel` not found.')
            ->assertExitCode(1);
    }

    /** @test */
    public function the_flush_command_does_not_proceed_when_the_model_count_is_0()
    {
        $this->artisan('sequence:flush \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Nothing to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_flush_command_does_not_update_values_that_are_already_null()
    {
        $group = Factory::of('group')->create();

        $items = Factory::of('Item')->times(4)->create(['group_id' => $group->id]);

        $items->each(function ($item) {
            $item->update(['position' => null]);

            $this->assertNull($item->position);
        });

        $this->artisan('sequence:flush \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Analyzing and flushing sequence values from 4 object(s).')
            ->expectsOutput('0 row(s) were updated.')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_flush_command_flushes_sequence_values()
    {
        $group = Factory::of('group')->create();

        $firstItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $secondItem = Factory::of('Item')->create(['group_id' => $group->id]);
        $thirdItem = Factory::of('Item')->create(['group_id' => $group->id]);

        $secondItem->update(['position' => null]);

        $thirdItem->update(['position' => null]);

        $this->assertNotNull($firstItem->position);
        $this->assertNull($secondItem->position);
        $this->assertNull($thirdItem->position);

        $this->artisan('sequence:flush \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Analyzing and flushing sequence values from 3 object(s).')
            ->expectsOutPut('1 row(s) were updated.')
            ->assertExitCode(0);

        $this->assertNull($firstItem->refresh()->position);
        $this->assertNull($secondItem->refresh()->position);
        $this->assertNull($thirdItem->refresh()->position);
    }
}
