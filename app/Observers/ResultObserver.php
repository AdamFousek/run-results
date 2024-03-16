<?php

namespace App\Observers;


use App\Commands\Results\RemoveResultCommand;
use App\Jobs\RecalculateTopResults;
use App\Models\Illuminate\Result;
use App\Services\Providers\ResultStatsService;

readonly class ResultObserver
{
    public function __construct(
        private ResultStatsService $resultStatsService,
        private RemoveResultCommand $removeResultCommand,
    ) {
    }

    /**
     * Handle the Result "created" event.
     */
    public function created(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();

        $tag = (string)$result->race->tag;
        if ($tag !== '') {
            $this->resultStatsService->invalidateCache($tag);
            RecalculateTopResults::dispatch($tag);
        }
    }

    /**
     * Handle the Result "updated" event.
     */
    public function updated(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();

        $tag = (string)$result->race->tag;
        if ($tag !== '') {
            $this->resultStatsService->invalidateCache($tag);
            RecalculateTopResults::dispatch($tag);
        }
    }

    /**
     * Handle the Result "deleted" event.
     */
    public function deleted(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();

        $tag = (string)$result->race->tag;
        if ($tag !== '') {
            $this->resultStatsService->invalidateCache($tag);
            RecalculateTopResults::dispatch($tag);
        }

        $this->removeResultCommand->handle($result->id);
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
