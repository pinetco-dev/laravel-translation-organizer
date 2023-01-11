<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pinetcodev\LaravelTranslationOrganizer\LaravelTranslationOrganizer
 */
class LaravelTranslationOrganizer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Pinetcodev\LaravelTranslationOrganizer\LaravelTranslationOrganizer::class;
    }
}
