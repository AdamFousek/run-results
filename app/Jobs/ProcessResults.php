<?php

namespace App\Jobs;

use App\Models\UploadFileResult;
use App\Services\HandleUploadFileResultService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessResults implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        #[WithoutRelations]
        public UploadFileResult $uploadFileResult,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(HandleUploadFileResultService $service): void
    {
        $service->handle($this->uploadFileResult);
    }
}
