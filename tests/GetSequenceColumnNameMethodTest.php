<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Factories\Factory;
use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Models\Item;

class GetSequenceColumnNameMethodTest extends TestCase
{
    /** @test */
    public function column_name_may_be_set_in_the_configuration_file()
    {
        config(['eloquentsequencer.column_name' => 'order']);

        $group = Factory::of('Group')->create();

        $this->assertEquals('order', Item::getSequenceColumnName());
    }
}
