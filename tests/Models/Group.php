<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
