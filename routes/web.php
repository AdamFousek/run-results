<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', \App\Http\Controllers\WelcomeController::class)->name('welcome');

Route::post('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('search');

Route::get('/zavodnici', [\App\Http\Controllers\RunnerController::class, 'index'])->name('runners.index');
Route::get('/zavodnik/{runner}', [\App\Http\Controllers\RunnerController::class, 'show'])->name('runners.show');

Route::get('/zavody', [\App\Http\Controllers\RaceController::class, 'index'])->name('races.index');
Route::get('/zavody/{race:slug}', [\App\Http\Controllers\RaceController::class, 'show'])->name('races.show');
Route::get('/zavody/{race:slug}/statistiky', [\App\Http\Controllers\RaceController::class, 'stats'])->name('races.stats');
Route::get('/zavody/{race:slug}/statistiky/nejlepsi-muzi', [\App\Http\Controllers\RaceController::class, 'topMen'])->name('races.stats.topMen');
Route::get('/zavody/{race:slug}/statistiky/nejlepsi-zeny', [\App\Http\Controllers\RaceController::class, 'topWomen'])->name('races.stats.topWomen');
Route::get('/zavody/{race:slug}/statistiky/nejvice-ucasti', [\App\Http\Controllers\RaceController::class, 'topParticipant'])->name('races.stats.topParticipant');

Route::get('/clanky', [\App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
Route::get('/clanek/{article:slug}', [\App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/runner-pair', [\App\Http\Controllers\PairRunnerLogController::class, 'store'])->name('profile.runner.pair');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/zavodnici', [\App\Http\Controllers\Admin\RunnerController::class, 'index'])->name('admin.runners.index');
    Route::get('/admin/zavodnici/{runner}/edit', [\App\Http\Controllers\Admin\RunnerController::class, 'edit'])->name('admin.runners.edit');
    Route::post('/admin/zavodnici/{runner}/edit', [\App\Http\Controllers\Admin\RunnerController::class, 'update'])->name('admin.runners.update');
    Route::get('/admin/zavodnici/vytvorit', [\App\Http\Controllers\Admin\RunnerController::class, 'create'])->name('admin.runners.create');
    Route::post('/admin/zavodnici/vytvorit', [\App\Http\Controllers\Admin\RunnerController::class, 'store'])->name('admin.runners.store');
    Route::delete('/admin/zavodnici/{runner}/smazat', [\App\Http\Controllers\Admin\RunnerController::class, 'destroy'])->name('admin.runners.destroy');
    Route::post('/admin/zavodnici/{runner}/merge', [\App\Http\Controllers\Admin\RunnerController::class, 'merge'])->name('admin.runners.merge');
    Route::get('/admin/zavodnici/duplicity', \App\Http\Controllers\Admin\DuplicityController::class)->name('admin.runners.duplicity');

    Route::get('/admin/zavody', [\App\Http\Controllers\Admin\RaceController::class, 'index'])->name('admin.races.index');
    Route::get('/admin/zavody/vytvorit', [\App\Http\Controllers\Admin\RaceController::class, 'create'])->name('admin.races.create');
    Route::post('/admin/zavody/vytvorit', [\App\Http\Controllers\Admin\RaceController::class, 'store'])->name('admin.races.store');
    Route::get('/admin/zavody/{race}/edit', [\App\Http\Controllers\Admin\RaceController::class, 'edit'])->name('admin.races.edit');
    Route::post('/admin/zavody/{race}/edit', [\App\Http\Controllers\Admin\RaceController::class, 'update'])->name('admin.races.update');
    Route::delete('/admin/zavody/{race}/smazat', [\App\Http\Controllers\Admin\RaceController::class, 'destroy'])->name('admin.races.destroy');
    Route::post('/admin/zavody/{race}/upload-file', [\App\Http\Controllers\Admin\RaceController::class, 'uploadFile'])->name('admin.races.fileUpload');

    Route::get('/admin/vysledky', [\App\Http\Controllers\Admin\ResultController::class, 'index'])->name('admin.results.index');
    Route::get('/admin/vysledky/{race}', [\App\Http\Controllers\Admin\ResultController::class, 'show'])->name('admin.results.show');
    Route::post('/admin/vysledky/{race}/upload', [\App\Http\Controllers\Admin\ResultController::class, 'upload'])->name('admin.results.upload');
    Route::post('/admin/vysledky/vytvorit', [\App\Http\Controllers\Admin\ResultController::class, 'store'])->name('admin.results.store');
    Route::post('/admin/vysledky/{result}/upravit', [\App\Http\Controllers\Admin\ResultController::class, 'update'])->name('admin.results.update');
    Route::delete('/admin/vysledky/{result}/smazat', [\App\Http\Controllers\Admin\ResultController::class, 'destroy'])->name('admin.results.destroy');
    Route::delete('/admin/vysledky/{race}/smazat-vsechny', [\App\Http\Controllers\Admin\ResultController::class, 'destroyAll'])->name('admin.results.destroyAll');

    Route::post('/admin/soubory/{uploadedFiles}/togglePublicity', [\App\Http\Controllers\Admin\UploadedFilesController::class, 'togglePublicity'])->name('admin.uploadedFiles.togglePublicity');
    Route::delete('/admin/soubory/{uploadedFiles}/smazat', [\App\Http\Controllers\Admin\UploadedFilesController::class, 'destroy'])->name('admin.uploadedFiles.destroy');

    Route::get('/admin/uzivatele', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/uzivatele/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');

    Route::get('/admin/mereni', [\App\Http\Controllers\Admin\MeasurementController::class, 'index'])->name('admin.measurement.index');

    Route::get('/admin/clanky', [ArticleController::class, 'index'])->name('admin.articles.index');
    Route::get('/admin/clanky/vytvorit', [ArticleController::class, 'create'])->name('admin.articles.create');
    Route::post('/admin/clanky/vytvorit', [ArticleController::class, 'store'])->name('admin.articles.store');
    Route::get('/admin/clanky/{article}', [ArticleController::class, 'edit'])->name('admin.articles.edit');
    Route::post('/admin/clanky/{article}', [ArticleController::class, 'update'])->name('admin.articles.update');
    Route::delete('/admin/clanky/{article}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');

    Route::get('/admin/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
});

require __DIR__.'/auth.php';
