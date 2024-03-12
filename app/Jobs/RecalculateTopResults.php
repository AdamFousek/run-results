<?php

namespace App\Jobs;

use App\Services\RecalculateTopResultsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateTopResults implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $raceTag,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(RecalculateTopResultsService $recalculateTopResultsService): void
    {
        $recalculateTopResultsService->handle($this->raceTag);
    }
}
