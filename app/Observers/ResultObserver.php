<?php

namespace App\Observers;


use App\Models\Illuminate\Result;

class ResultObserver
{
    /**
     * Handle the Result "created" event.
     */
    public function created(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();
    }

    /**
     * Handle the Result "updated" event.
     */
    public function updated(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();
    }

    /**
     * Handle the Result "deleted" event.
     */
    public function deleted(Result $result): void
    {
        $result->race->searchable();
        $result->runner->searchable();
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
