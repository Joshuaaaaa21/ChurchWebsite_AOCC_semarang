<?php

use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\SermonController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', [LandingController::class, 'index'])->name('home');

Route::prefix('sermon')->name('sermons.')->group(function () {
    Route::get('/', [SermonController::class, 'index'])->name('index');
    Route::get('/{slug}', [SermonController::class, 'show'])->name('show');
});

Route::prefix('acara')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{slug}', [EventController::class, 'show'])->name('show');
});
