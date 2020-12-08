<?php

namespace Gurgentil\LaravelEloquentSequencer\Exceptions;

use InvalidArgumentException;

class SequenceValueOutOfBoundsException extends InvalidArgumentException
{
    /**
     * Create exception.
     *
     * @param int $value
     *
     * @return self
     */
    public static function create(int $value): self
    {
        return new static("Sequence value `{$value}` is out of bounds.");
    }
}
