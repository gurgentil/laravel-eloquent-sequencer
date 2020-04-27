<?php

namespace Gurgentil\LaravelEloquentSequencer\Tests\Factories;

use Exception;
use Illuminate\Support\Collection;

class Factory
{
    protected $model;
    
    protected $times = 1;

    public function of(string $model)
    {
        $this->model = 'Gurgentil\\LaravelEloquentSequencer\\Tests\\Models\\' . $model;

        return $this;
    }

    public function times(int $times)
    {
        $this->times = $times;

        return $this;
    }

    public function create($params = [])
    {
        if (! $this->model) {
            throw new Exception('Factory must receive an existing model name.');
        }

        if ($this->times === 1) {
            return $this->createObject($params);
        }

        return Collection::times($this->times, function () use ($params) {
            return $this->createObject($params);
        });
    }

    protected function createObject($params)
    {
        return ($this->model)::create($params);
    }
}
