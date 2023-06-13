<?php

use Illuminate\Support\Facades\Route;
use Pinetcodev\LaravelTranslationOrganizer\Http\Controllers\TranslationController;

Route::middleware(config('translation-organizer.middleware_admin'))->group(function () {
    Route::get('/', [TranslationController::class, 'index'])->name('translation_organizer.index');
    Route::prefix('phrases')->group(function () {
        Route::get('/edit/{translation}', [TranslationController::class, 'phrase'])->name('translation_organizer.show');
    });
});

Route::middleware(config('translation-organizer.middleware'))->group(function () {
    Route::get('fetch/{id}', [TranslationController::class, 'fetch'])->name('translation_organizer.fetch');
    Route::post('toggle-translations', [TranslationController::class, 'toggle'])->name('translation_organizer.toggle');
    Route::any('toggle-translations-enable', [TranslationController::class, 'toggleEnable'])->name('translation_organizer.toggle-enable');
    Route::post('/', [TranslationController::class, 'store'])->name('translation_organizer.store');
});
