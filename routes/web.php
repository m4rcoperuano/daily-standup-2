<?php

use App\Http\Controllers\FetchLinkPreviewController;
use App\Http\Controllers\JiraController;
use App\Http\Controllers\SocialiteIntegrationController;
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
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

    Route::get('/stand-up-groups/{standUpGroup}/entries', [StandUpEntryController::class, 'index'])
        ->name('stand-up-entries.index');

    Route::resource('/stand-up-entries', StandUpEntryController::class)
        ->only(['store', 'update', 'destroy']);

    Route::get('/link-preview/{link}', FetchLinkPreviewController::class)
        ->name('link-preview.show');

    Route::get('auth/{provider}/redirect', [SocialiteIntegrationController::class, 'redirect'])
        ->name('socialite.redirect');
    Route::get('auth/{provider}/callback', [SocialiteIntegrationController::class, 'callback'])
        ->name('socialite.callback');
    Route::get('auth/{team}/integrations', [SocialiteIntegrationController::class, 'index'])
        ->name('socialite.index');
    Route::delete('auth/integrations/{socialiteIntegration}', [SocialiteIntegrationController::class, 'destroy'])
        ->name('socialite.destroy');

    Route::get('/teams/{team}/integrations', [SocialiteIntegrationController::class, "show"])
        ->name('teams.integrations.show');

    Route::prefix("integrations/{team}/jira")
        ->group(function() {
            Route::get("/boards", [JiraController::class, "boards"])
                ->name("integrations.jira.boards");

            Route::get("/boards/{boardId}/sprints", [JiraController::class, "sprints"])
                ->name("integrations.jira.sprints");

            Route::get("/sprints/{sprintId}", [JiraController::class, "sprint"])
                ->name("integrations.jira.sprint");
        });
});
