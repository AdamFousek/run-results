<?php

declare(strict_types=1);


namespace App\Providers;

use App\Repositories\Illuminate\IlluminateRunnerRepository;
use App\Repositories\IlluminateRunnerRepositoryInterface;
use App\Repositories\Meilisearch\MeilisearchRunnerRepository;
use App\Repositories\RunnerRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Meilisearch
        $this->app->bind(RunnerRepository::class, MeilisearchRunnerRepository::class);

        // Illuminate
        $this->app->bind(IlluminateRunnerRepositoryInterface::class, IlluminateRunnerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
