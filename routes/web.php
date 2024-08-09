<?php

use App\Http\Controllers\FetchLinkPreviewController;
use App\Http\Controllers\StandUpEntryController;
use App\Http\Controllers\StandUpGroupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('/stand-up-groups', StandUpGroupController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::get('/stand-up-groups/{standUpGroup}/entries', [StandUpEntryController::class, 'index'])
        ->name('stand-up-entries.index');

    Route::resource('/stand-up-entries', StandUpEntryController::class)
        ->only(['store', 'update', 'destroy']);

    Route::get('/link-preview/{link}', FetchLinkPreviewController::class)
        ->name('link-preview.show');
});
