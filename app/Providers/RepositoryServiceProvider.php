<?php

declare(strict_types=1);


namespace App\Providers;

use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\Illuminate\IlluminateRaceRepository;
use App\Repositories\Illuminate\IlluminateResultRepository;
use App\Repositories\Illuminate\IlluminateRunnerRepository;
use App\Repositories\Illuminate\IlluminateUploadFileResultRowRepository;
use App\Repositories\IlluminateRaceRepositoryInterface;
use App\Repositories\IlluminateResultRepositoryInterface;
use App\Repositories\IlluminateRunnerRepositoryInterface;
use App\Repositories\IlluminateUploadFileResultRowRepositoryInterface;
use App\Repositories\Meilisearch\MeilisearchArticleRepository;
use App\Repositories\Meilisearch\MeilisearchRaceRepositoryInterface;
use App\Repositories\Meilisearch\MeilisearchResultRepository;
use App\Repositories\Meilisearch\MeilisearchRunnerRepositoryInterface;
use App\Repositories\Meilisearch\MeilisearchTopResultRepository;
use App\Repositories\RaceRepositoryInterface;
use App\Repositories\ResultRepositoryInterface;
use App\Repositories\RunnerRepositoryInterface;
use App\Repositories\TopResultRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Meilisearch
        $this->app->bind(RunnerRepositoryInterface::class, MeilisearchRunnerRepositoryInterface::class);
        $this->app->bind(RaceRepositoryInterface::class, MeilisearchRaceRepositoryInterface::class);
        $this->app->bind(ResultRepositoryInterface::class, MeilisearchResultRepository::class);
        $this->app->bind(TopResultRepositoryInterface::class, MeilisearchTopResultRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, MeilisearchArticleRepository::class);

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
