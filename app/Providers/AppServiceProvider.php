<?php

namespace App\Providers;

use App\Engines\MeilisearchEngine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Meilisearch\Client as MeilisearchClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();

        resolve(EngineManager::class)->extend('meilisearch', function () {
            return new MeilisearchEngine(
                $this->app->make(MeilisearchClient::class),
                config('scout.soft_delete', false)
            );
        });
    }
}
