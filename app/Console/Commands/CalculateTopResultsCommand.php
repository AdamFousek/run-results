<?php

namespace App\Console\Commands;

use App\Commands\TopResult\UpsertTopResultHandler;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Illuminate\Result;
use App\Queries\Race\GetAllTagsHandler;
use App\Queries\Result\GetTopRunnersBy;
use App\Queries\Result\GetTopRunnersByQuery;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

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
        private readonly GetTopRunnersByQuery $getTopRunnersByQuery,
        private readonly GetAllTagsHandler $getAllTagsHandler,
        private readonly UpsertTopResultHandler $upsertTopResultHandler,
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

            $index = 0;
            do {
                $results = $this->getTopRunnersByQuery->handle(new GetTopRunnersBy(
                    raceTag: $tag,
                    gender: RunnerGenderEnum::MALE,
                    limit: 100,
                    offset: $index * 100,
                ));

                $results = $results->map(function (Result $result, int $key) use ($index) {
                    $result->setTopPosition(($key + 1) + ($index * 100));
                    return $result;
                });

                $this->line("Fount records: {$results->count()} for tag {$tag}... male");

                $this->upsertTopResultHandler->handle($results);

                $this->info('Processed ' . ($index + 1) * 100 . ' results for tag ' . $tag . '...');

                $index++;
            } while ($results->count() > 0);

            $index = 0;
            do {
                $results = $this->getTopRunnersByQuery->handle(new GetTopRunnersBy(
                    raceTag: $tag,
                    gender: RunnerGenderEnum::FEMALE,
                    limit: 100,
                    offset: $index * 100,
                ));

                $results = $results->map(function (Result $result, int $key) use ($index) {
                    $result->setTopPosition(($key + 1) + ($index * 100));
                    return $result;
                });

                $this->line("Fount records: {$results->count()} for tag {$tag}... female");

                $this->upsertTopResultHandler->handle($results);

                $this->info('Processed ' . ($index + 1) * 100 . ' results for tag ' . $tag . '...');

                $index++;
            } while ($results->count() > 0);
        }


    }
}
