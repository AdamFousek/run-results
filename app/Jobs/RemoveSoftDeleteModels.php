<?php

namespace App\Jobs;

use App\Models\Illuminate\Race;
use App\Models\Illuminate\Runner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveSoftDeleteModels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __invoke(): void
    {
        Runner::onlyTrashed()->forceDelete();
        Race::onlyTrashed()->forceDelete();
    }
}
