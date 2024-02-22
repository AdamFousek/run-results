<?php

namespace App\Observers;


use App\Models\Illuminate\Result;
use App\Services\ResultStatsService;

class ResultObserver
{
    public function __construct(
        private readonly ResultStatsService $resultStatsService,
    ) {
    }

    /**
     * Handle the Result "created" event.
     */
    public function created(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();

        $this->resultStatsService->invalidateCache($result->race->tag);
    }

    /**
     * Handle the Result "updated" event.
     */
    public function updated(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();

        $this->resultStatsService->invalidateCache($result->race->tag);
    }

    /**
     * Handle the Result "deleted" event.
     */
    public function deleted(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();

        $this->resultStatsService->invalidateCache($result->race->tag);
    }

    /**
     * Handle the Result "restored" event.
     */
    public function restored(Result $result): void
    {
        //
    }

    /**
     * Handle the Result "force deleted" event.
     */
    public function forceDeleted(Result $result): void
    {
        //
    }
}
