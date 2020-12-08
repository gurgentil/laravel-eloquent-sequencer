<?php

namespace Gurgentil\LaravelEloquentSequencer\Console\Commands;

use Exception;
use Illuminate\Console\Command as IlluminateCommand;

abstract class Command extends IlluminateCommand
{
    /**
     * Get model argument.
     *
     * @return string
     * @throws Exception
     */
    protected function getModelArgument(): string
    {
        $modelClass = $this->argument('model') ?? '';

        $modelClass = (string) $modelClass;

        if (! class_exists($modelClass)) {
            $message = "Model `{$modelClass}` not found.";

            $this->error($message);

            throw new Exception($message);
        }

        return $modelClass;
    }
}
