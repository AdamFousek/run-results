<?php

declare(strict_types=1);


namespace App\Providers;

use App\Repositories\Illuminate\IlluminateRaceRepository;
use App\Repositories\Illuminate\IlluminateResultRepository;
use App\Repositories\Illuminate\IlluminateRunnerRepository;
use App\Repositories\Illuminate\IlluminateUploadFileResultRowRepository;
use App\Repositories\IlluminateRaceRepositoryInterface;
use App\Repositories\IlluminateResultRepositoryInterface;
use App\Repositories\IlluminateRunnerRepositoryInterface;
use App\Repositories\IlluminateUploadFileResultRowRepositoryInterface;
use App\Repositories\Meilisearch\MeilisearchRaceRepository;
use App\Repositories\Meilisearch\MeilisearchResultRepository;
use App\Repositories\Meilisearch\MeilisearchRunnerRepository;
use App\Repositories\Meilisearch\MeilisearchTopResultRepository;
use App\Repositories\RaceRepository;
use App\Repositories\ResultRepositoryInterface;
use App\Repositories\RunnerRepository;
use App\Repositories\TopResultRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Meilisearch
        $this->app->bind(RunnerRepository::class, MeilisearchRunnerRepository::class);
        $this->app->bind(RaceRepository::class, MeilisearchRaceRepository::class);
        $this->app->bind(ResultRepositoryInterface::class, MeilisearchResultRepository::class);
        $this->app->bind(TopResultRepositoryInterface::class, MeilisearchTopResultRepository::class);

        // Illuminate
        $this->app->bind(IlluminateRunnerRepositoryInterface::class, IlluminateRunnerRepository::class);
        $this->app->bind(IlluminateResultRepositoryInterface::class, IlluminateResultRepository::class);
        $this->app->bind(IlluminateUploadFileResultRowRepositoryInterface::class, IlluminateUploadFileResultRowRepository::class);
        $this->app->bind(IlluminateRaceRepositoryInterface::class, IlluminateRaceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
