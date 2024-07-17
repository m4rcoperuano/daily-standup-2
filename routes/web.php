<?php

use App\Http\Controllers\StandUpController;
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

    Route::get('/stand-up-groups/index', [StandUpGroupController::class, 'index'])
        ->name('stand-up-groups.index');

    Route::get('/stand-up-groups/create', [StandUpGroupController::class, 'create'])
        ->name('stand-up-groups.create');

    Route::get('/stand-up-groups/{standUpGroup}', [StandUpGroupController::class, 'show'])
        ->name('stand-up-groups.show');

    Route::post('/stand-up-groups', [StandUpGroupController::class, 'store'])
        ->name('stand-up-groups.store');
});
