<?php

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/zavodnici', [\App\Http\Controllers\RunnerController::class, 'index'])->name('runners.index');
Route::get('/zavodnik/{runner}', [\App\Http\Controllers\RunnerController::class, 'show'])->name('runners.show');

Route::get('/zavody', [\App\Http\Controllers\RaceController::class, 'index'])->name('races.index');
Route::get('/zavody/{race}', [\App\Http\Controllers\RaceController::class, 'show'])->name('races.show');

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
    Route::delete('/admin/zavodnici/{runner}/delete', [\App\Http\Controllers\Admin\RunnerController::class, 'destroy'])->name('admin.runners.destroy');

    Route::get('/admin/zavody', [\App\Http\Controllers\Admin\RaceController::class, 'index'])->name('admin.races.index');

    Route::get('/admin/uzivatele', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/uzivatele/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
});

require __DIR__.'/auth.php';
