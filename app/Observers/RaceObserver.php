<?php

namespace App\Observers;

use App\Commands\Race\RemoveRaceHandler;
use App\Models\Illuminate\Race;

readonly class RaceObserver
{
    public function __construct(
        private RemoveRaceHandler $removeRaceCommand
    ) {
    }
    /**
     * Handle the Race "deleted" event.
     */
    public function deleted(Race $race): void
    {
        $this->removeRaceCommand->handle($race->id);
    }
}
