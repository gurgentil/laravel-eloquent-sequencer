<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Gurgentil\LaravelEloquentSequencer\SequencingStrategy;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class SequencingStrategyTest extends TestCase
{
    /** @test **/
    public function it_can_be_set_to_always()
    {
        $this->assertEquals('always', SequencingStrategy::ALWAYS);
    }
}
