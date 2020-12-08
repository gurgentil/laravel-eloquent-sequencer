<?php

namespace Gurgentil\LaravelEloquentSequencer\Console\Commands;

use Illuminate\Support\Facades\DB;

class FlushSequenceValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sequence:flush {model : The model to be flushed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush sequence values from a specific model.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function () {
            $class = $this->getModelArgument();

            $models = ($class)::all();

            if ($models->count() === 0) {
                $this->info('Nothing to update.');

                return;
            }

            $this->line("Analyzing and flushing sequence values from {$models->count()} object(s).");

            $columnName = ($class)::getSequenceColumnName();

            $modelsToUpdate = $models->where($columnName, '!=', null);

            $modelsToUpdate->each(function ($model) use ($columnName) {
                $model->withoutSequencing()->update([
                    $columnName => null,
                ]);
            });

            $this->info("{$modelsToUpdate->count()} row(s) were updated.");
        });
    }
}
