<?php

namespace App\Observers;

use App\Commands\Runner\RemoveRunnerHandler;
use App\Models\Illuminate\Runner;

readonly class RunnerObserver
{
    public function __construct(
        private RemoveRunnerHandler $removeRaceCommand
    ) {
    }
    /**
     * Handle the Runner "created" event.
     */
    public function created(Runner $runner): void
    {
        //
    }

    /**
     * Handle the Runner "updated" event.
     */
    public function updated(Runner $runner): void
    {
        //
    }

    /**
     * Handle the Runner "deleted" event.
     */
    public function deleted(Runner $runner): void
    {
        $this->removeRaceCommand->handle($runner->id);
    }

    /**
     * Handle the Runner "restored" event.
     */
    public function restored(Runner $runner): void
    {
        //
    }

    /**
     * Handle the Runner "force deleted" event.
     */
    public function forceDeleted(Runner $runner): void
    {
        //
    }
}
