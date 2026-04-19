<?php

use App\Http\Controllers\StandUpEntryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth:sanctum'
    ],
], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/stand-up-groups/{standUpGroup}/stand-up-entries', [StandUpEntryController::class, 'index']);
    Route::apiResource('stand-up-entries', StandUpEntryController::class)
        ->only(['store', 'update', 'destroy']);
});
