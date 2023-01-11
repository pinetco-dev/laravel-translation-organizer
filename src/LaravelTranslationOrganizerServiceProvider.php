<?php

namespace Pinetcodev\LaravelTranslationOrganizer;

use Pinetcodev\LaravelTranslationOrganizer\Commands\FindCommand;
use Pinetcodev\LaravelTranslationOrganizer\Commands\ImportCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTranslationOrganizerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */

        $package
            ->name('laravel-translation-organizer')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_translation_organizer_table')
            ->hasCommands(ImportCommand::class, FindCommand::class);
    }
}
