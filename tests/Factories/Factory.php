<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Factories;

use Illuminate\Database\Eloquent\Model;

class Factory
{
    protected $model;

    public function of(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function create($params = []): Model
    {
        return ($this->model)::create($params);
    }
}
