<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TopicController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('topics', TopicController::class);
    Route::post('topics/{topic}/vote', [TopicController::class, 'vote'])->name('topics.vote');
});