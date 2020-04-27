<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Models;

use Gurgentil\LaravelEloquentSequencer\Traits\Sequenceable;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use Sequenceable;

    protected $fillable = [
        'group_id',
        'position',
    ];

    public static $sequenceableKeys = [
        'group_id',
    ];
}
