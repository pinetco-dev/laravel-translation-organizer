<?php

use Illuminate\Support\Facades\Route;
use Pinetcodev\LaravelTranslationOrganizer\Http\Controllers\TranslationController;

Route::get('/', [TranslationController::class, 'index'])->name('translation_organizer.index');

Route::prefix('phrases')->group(function () {
    Route::get('/edit/{translation}', [TranslationController::class, 'phrase'])->name('translation_organizer.show');
});
