<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Unit;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Models\Item;
use Gurgentil\LaravelEloquentSequencer\Tests\TestCase;

class GetAllowNullMethodTest extends TestCase
{
    /** @test */
    public function allow_null_may_be_set_in_the_configuration_file()
    {
        config(['eloquentsequencer.allow_null' => 'true']);

        $group = Factory::of('Group')->create();

        $this->assertEquals(true, Item::getAllowNull());
    }
}
