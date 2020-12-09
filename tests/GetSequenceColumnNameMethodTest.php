<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests;

use Facades\Gurgentil\LaravelEloquentSequencer\Tests\Models\Item;

class GetSequenceColumnNameMethodTest extends TestCase
{
    /** @test */
    public function the_column_name_can_be_set_in_the_configuration_file(): void
    {
        $columnName = 'order';

        config(['eloquentsequencer.column_name' => $columnName]);

        $this->assertEquals($columnName, Item::getSequenceColumnName());
    }
}
