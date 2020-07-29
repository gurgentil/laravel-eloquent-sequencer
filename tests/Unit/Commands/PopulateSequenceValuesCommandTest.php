<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit\Commands;

use Exception;
use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class PopulateSequenceValuesCommandTest extends TestCase
{
    /** @test */
    public function the_populate_command_requires_a_valid_model_name()
    {
        $this->expectException(Exception::class);

        $this->artisan('sequence:populate \\\App\\\InvalidModel')
            ->expectsOutput('Class `\\App\\InvalidModel` not found.')
            ->assertExitCode(1);
    }

    /** @test */
    public function the_populate_command_does_not_proceed_when_the_model_count_is_0()
    {
        $this->artisan('sequence:populate \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Nothing to update.')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_populate_command_does_not_update_values_that_are_already_assigned()
    {
        $group = Factory::of('group')->create();

        $items = Factory::of('Item')
            ->times(4)
            ->create(['group_id' => $group->id]);

        $items->each(function ($item) {
            $this->assertNotNull($item->position);
        });

        $this->artisan('sequence:populate \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Analyzing and populating sequence values in 4 object(s).')
            ->expectsOutput('0 row(s) were updated.')
            ->assertExitCode(0);
    }

    /** @test */
    public function the_populate_command_populates_every_empty_sequence_value()
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

        $this->artisan('sequence:populate \\\Gurgentil\\\LaravelEloquentSequencer\\\Tests\\\Models\\\Item')
            ->expectsOutput('Analyzing and populating sequence values in 3 object(s).')
            ->expectsOutPut('2 row(s) were updated.')
            ->assertExitCode(0);

        $this->assertEquals(1, $firstItem->refresh()->position);
        $this->assertEquals(2, $secondItem->refresh()->position);
        $this->assertEquals(3, $thirdItem->refresh()->position);
    }
}
