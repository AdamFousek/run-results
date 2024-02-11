<?php

namespace App\Jobs;

use App\Models\Runner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveUnusedRunners implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function __invoke(): void
    {
        Runner::query()->selectRaw('count(results.id) as count, runners.id')
            ->leftJoin('results', 'runners.id', '=', 'results.runner_id')
            ->having('count', '=', 0)
            ->groupBy('runners.id')
            ->delete();
    }
}
