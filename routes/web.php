<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TopicController::class, 'index'])->name('topics.index');
Route::get('/topics/{topic}', [TopicController::class, 'show'])->name('topics.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/topics/{topic}/vote', [VoteController::class, 'store'])->name('votes.store');
    Route::delete('/topics/{topic}/vote', [VoteController::class, 'destroy'])->name('votes.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/topics/{topic}/close', [AdminTopicController::class, 'close'])->name('topics.close');
    Route::resource('topics', AdminTopicController::class);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
