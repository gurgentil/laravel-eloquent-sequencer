<?php

namespace Gurgentil\LaravelEloquentSequencer\Traits;

use Gurgentil\LaravelEloquentSequencer\Exceptions\SequenceValueOutOfBoundsException;
use Gurgentil\LaravelEloquentSequencer\SequencingStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait Sequenceable
{
    /**
     * Indicates if the model should be sequenced.
     *
     * @var bool
     */
    protected $shouldBeSequenced = true;

    /**
     * Handle lifecycle hooks.
     *
     * @return void
     */
    public static function bootSequenceable(): void
    {
        static::creating(function ($model) {
            $model->handleSequenceableCreate();
        });

        static::updating(function ($model) {
            $model->handleSequenceableUpdate();
        });

        static::deleting(function ($model) {
            $model->handleSequenceableDelete();
        });
    }

    /**
     * Disable sequencing.
     *
     * @return self
     */
    public function withoutSequencing(): self
    {
        $this->shouldBeSequenced = false;

        return $this;
    }

    /**
     * Handle sequenceable creation.
     *
     * @return void
     */
    protected function handleSequenceableCreate(): void
    {
        $value = $this->getSequenceValue();

        if (static::strategyIn([
            SequencingStrategy::NEVER,
            SequencingStrategy::ON_UPDATE,
        ])) {
            return;
        }

        if (is_null($value)) {
            $this->{static::getSequenceColumnName()} = $this->getNextSequenceValue();
        }

        if ($this->isNewSequenceValueOutOfBounds()) {
            throw SequenceValueOutOfBoundsException::create($value);
        }

        static::updateSequenceablesAffectedBy($this);
    }

    /**
     * Handle sequenceable update.
     *
     * @return void
     */
    protected function handleSequenceableUpdate(): void
    {
        if (static::strategyIn([
            SequencingStrategy::NEVER,
            SequencingStrategy::ON_CREATE,
        ])) {
            return;
        }

        if (! $this->shouldBeSequenced) {
            $this->shouldBeSequenced = true;

            return;
        }

        $value = $this->getSequenceValue();

        if ($this->isClean(static::getSequenceColumnName()) || is_null($value)) {
            return;
        }

        if ($this->isUpdatedSequenceValueOutOfBounds()) {
            throw SequenceValueOutOfBoundsException::create($value);
        }

        static::updateSequenceablesAffectedBy($this);
    }

    /**
     * Handle sequenceable delete.
     *
     * @return void
     */
    protected function handleSequenceableDelete(): void
    {
        if (static::strategyIn([
            SequencingStrategy::NEVER,
            SequencingStrategy::ON_CREATE,
            SequencingStrategy::ON_UPDATE,
        ])) {
            return;
        }

        if (! $this->shouldBeSequenced) {
            $this->shouldBeSequenced = true;

            return;
        }

        $columnName = static::getSequenceColumnName();

        $objects = $this->getSequence()
            ->where($columnName, '>', $this->getSequenceValue());

        static::decrementSequenceValues($objects);
    }

    /**
     * Determine if strategy is in array.
     *
     * @param array $strategies
     *
     * @return bool
     */
    protected static function strategyIn(array $strategies): bool
    {
        return in_array(config('eloquentsequencer.strategy'), $strategies);
    }

    /**
     * Determine if new sequence value is out of bounds.
     *
     * @return bool
     */
    protected function isNewSequenceValueOutOfBounds(): bool
    {
        $newValue = $this->getSequenceValue();

        return $newValue <= 0 || $newValue > $this->getNextSequenceValue();
    }

    /**
     * Determine if updated sequence value is out of bounds.
     *
     * @return bool
     */
    protected function isUpdatedSequenceValueOutOfBounds(): bool
    {
        $newValue = $this->getSequenceValue();
        $originalValue = $this->getOriginalSequenceValue();

        return $newValue < static::getInitialSequenceValue()
            || ! is_null($originalValue) && $newValue > $this->getLastSequenceValue()
            || is_null($originalValue) && $newValue > $this->getNextSequenceValue();
    }

    /**
     * Decrement sequence values from a collection of sequenceable models.
     *
     * @param Collection $models
     *
     * @return void
     */
    protected static function decrementSequenceValues(Collection $models): void
    {
        $models->each(function ($model) {
            $model->decrement(static::getSequenceColumnName());
        });
    }

    /**
     * Increment sequence values from a collection of sequenceable models.
     *
     * @param Collection $models
     *
     * @return void
     */
    protected static function incrementSequenceValues(Collection $models): void
    {
        $models->each(function ($model) {
            $model->increment(static::getSequenceColumnName());
        });
    }

    /**
     * Get sequence value.
     *
     * @return int|null
     */
    public function getSequenceValue(): ?int
    {
        $value = $this->{static::getSequenceColumnName()};

        return is_numeric($value) ? (int) $value : null;
    }

    /**
     * Get original sequence value.
     *
     * @return int|null
     */
    protected function getOriginalSequenceValue(): ?int
    {
        $value = $this->getOriginal(static::getSequenceColumnName());

        return is_numeric($value) ? (int) $value : null;
    }

    /**
     * Update models affected by the repositioning of another model.
     *
     * @param Model $model
     *
     * @return void
     */
    protected static function updateSequenceablesAffectedBy(Model $model): void
    {
        $model->getConnection()->transaction(function () use ($model) {
            $modelsToUpdate = $model->getSequence()
                ->where('id', '!=', $model->id)
                ->filter(function ($sequenceModel) use ($model) {
                    return $sequenceModel->isAffectedByRepositioningOf($model);
                })
                ->each->withoutSequencing();

            if ($model->isMovingUpInSequence()) {
                static::decrementSequenceValues($modelsToUpdate);
            } else {
                static::incrementSequenceValues($modelsToUpdate);
            }
        });
    }

    /**
     * Determine if the model is moving up in the sequence.
     *
     * @return bool
     */
    protected function isMovingUpInSequence(): bool
    {
        $originalValue = $originalValue ?? $this->getOriginalSequenceValue();

        return $originalValue && $originalValue < $this->getSequenceValue();
    }

    /**
     * Determine if model is moving down in the sequence.
     *
     * @return bool
     */
    protected function isMovingDownInSequence(): bool
    {
        $originalValue = $this->getOriginalSequenceValue();

        return $originalValue && $originalValue > $this->getSequenceValue();
    }

    /**
     * Indicate if model is affected by the repositioning of another model in the sequence.
     *
     * @param Model $model
     *
     * @return bool
     */
    protected function isAffectedByRepositioningOf(Model $model): bool
    {
        $newValue = $model->getSequenceValue();
        $originalValue = $model->getOriginalSequenceValue();

        if ($model->isMovingDownInSequence()) {
            return $this->getSequenceValue() >= $newValue
                && $this->getSequenceValue() < $originalValue;
        }

        if ($model->isMovingUpInSequence()) {
            return $this->getSequenceValue() <= $newValue
                && $this->getSequenceValue() > $originalValue;
        }

        return $this->getSequenceValue() >= $newValue;
    }

    /**
     * Get name of the column that stores the sequence value.
     *
     * @return string
     */
    public static function getSequenceColumnName(): string
    {
        return (string) property_exists(static::class, 'sequenceable')
            ? static::$sequenceable
            : config('eloquentsequencer.column_name', 'position');
    }

    /**
     * Get sequence value of the last model in the sequence.
     *
     * @return int|null
     */
    protected function getLastSequenceValue(): ?int
    {
        $column = static::getSequenceColumnName();

        return $this->getSequence()->max($column);
    }

    /**
     * Get sequence value for the next model in the sequence.
     *
     * @return int
     */
    public function getNextSequenceValue(): int
    {
        $column = static::getSequenceColumnName();
        $maxSequenceValue = $this->getSequence()->max($column);

        return $this->getSequence()->count() === 0
            ? static::getInitialSequenceValue()
            : $maxSequenceValue + 1;
    }

    /**
     * Scope a query to order by sequence value.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public static function scopeSequenced(Builder $query): Builder
    {
        return $query->orderBy(static::getSequenceColumnName());
    }

    /**
     * Get a list of the models in the sequence.
     *
     * @return Collection
     */
    protected function getSequence(): Collection
    {
        $columnName = static::getSequenceColumnName();

        return static::where($this->getSequenceQueryConstraints())
            ->select($this->getSequenceQuerySelectColumns())
            ->where($columnName, '!=', null)
            ->orderBy($columnName)
            ->get();
    }

    /**
     * Get sequence query constraints.
     *
     * @return array
     */
    protected function getSequenceQueryConstraints(): array
    {
        $constraints = [];

        foreach ($this->getSequenceKeys() as $key) {
            $constraints[$key] = $this->$key;
        }

        return $constraints;
    }

    /**
     * Get keys to be selected in the query.
     *
     * @return array
     */
    protected function getSequenceQuerySelectColumns(): array
    {
        $primaryKey = $this->getKeyName();
        $sequenceColumnName = static::getSequenceColumnName();

        $columns = [
            $primaryKey,
            $sequenceColumnName,
        ];

        foreach ($this->getSequenceKeys() as $key) {
            array_push($columns, $key);
        }

        return $columns;
    }

    /**
     * Get sequence keys.
     *
     * @return array
     */
    protected function getSequenceKeys(): array
    {
        return property_exists(static::class, 'sequenceableKeys')
            ? (array) static::$sequenceableKeys
            : [];
    }

    /**
     * Get the value that sequences should start at.
     *
     * @return int
     */
    protected static function getInitialSequenceValue(): int
    {
        return (int) config('eloquentsequencer.initial_value', 1);
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    abstract public function getKeyName(): string;

    /**
     * Determine if the model and all the given attribute(s) have remained the same.
     *
     * @param array|string|null $attributes
     * @return bool
     */
    abstract public function isClean($attributes): bool;
}
