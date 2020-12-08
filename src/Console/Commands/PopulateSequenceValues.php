<?php

namespace Gurgentil\LaravelEloquentSequencer\Console\Commands;

use Illuminate\Support\Facades\DB;

class PopulateSequenceValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sequence:populate {model : The model to be populated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate sequence values for a specific model.';

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
                return $this->info('Nothing to update.');
            }

            $this->line("Analyzing and populating sequence values in {$models->count()} object(s).");

            $columnName = ($class)::getSequenceColumnName();

            $modelsToUpdate = $models->where($columnName, null);

            $modelsToUpdate->each(function ($model) use ($columnName) {
                $model->withoutSequencing()->update([
                    $columnName => $model->getNextSequenceValue(),
                ]);
            });

            $this->info("{$modelsToUpdate->count()} row(s) were updated.");
        });
    }
}
