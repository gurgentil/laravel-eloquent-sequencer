<?php

namespace Gurgentil\LaravelEloquentSequencer\Console\Commands\Traits;

use Exception;

trait HasModelArgument
{
    /**
     * Get model argument.
     *
     * @return string
     */
    protected function getModelArgument(): string
    {
        $class = $this->argument('model');

        if (!class_exists($class)) {
            $message = "Class `{$class}` not found.";

            $this->error($message);

            throw new Exception($message);
        }

        return $class;
    }
}
