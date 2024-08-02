<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('api.search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/profile/runners', \App\Http\Controllers\Api\SearchRunnerController::class)->name('api.runners.search');

    Route::post('/admin/runners/{runner}/search', \App\Http\Controllers\Api\SearchMergerRunnerController::class)->name('api.admin.runners.search');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/admin/results/availableRunners', \App\Http\Controllers\Api\Results\AvailableRunnersController::class)->name('api.results.availableRunners');

    Route::post('/admin/entity/reloadEntity', \App\Http\Controllers\Api\ReloadMeilisearchDataController::class)->name('api.meilisearch.reloadEntity');

    // Settings
    Route::post('/admin/settings/reloadRunners', [\App\Http\Controllers\Api\SettingsController::class, 'reloadRunners'])->name('api.settings.reloadRunners');
    Route::post('/admin/settings/reloadRaces', [\App\Http\Controllers\Api\SettingsController::class, 'reloadRaces'])->name('api.settings.reloadRaces');
    Route::post('/admin/settings/reloadResults', [\App\Http\Controllers\Api\SettingsController::class, 'reloadResults'])->name('api.settings.reloadResults');
});
