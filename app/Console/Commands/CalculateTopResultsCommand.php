<?php

namespace App\Console\Commands;

use App\Queries\Race\GetAllTagsHandler;
use App\Services\RecalculateTopResultsService;
use Illuminate\Console\Command;

class CalculateTopResultsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-top-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate top results for all race tags';

    public function __construct(
        private readonly GetAllTagsHandler $getAllTagsHandler,
        private readonly RecalculateTopResultsService $recalculateTopResultsService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tags = $this->getAllTagsHandler->handle();

        $this->info('Starting with tags: ' . implode(', ', $tags) . '...');

        foreach ($tags as $tag) {
            if ($tag === '') {
                continue;
            }

            $this->recalculateTopResultsService->handle($tag);
        }
    }
}
