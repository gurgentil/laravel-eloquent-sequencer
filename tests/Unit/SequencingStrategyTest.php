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

    /** @test **/
    public function it_can_be_set_to_on_create()
    {
        $this->assertEquals('on_create', SequencingStrategy::ON_CREATE);
    }
}
