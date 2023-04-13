<?php

// config for Pinetcodev/LaravelTranslationOrganizer

return [

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */
    'enabled' => env('TRANSLATION_ENABLED', false),
    'enabled_on_page' => env('TRANSLATION_ON_PAGE', false),
    'highlight-color' => 'aqua',
    'route' => [
        'prefix' => 'translations',
        'middleware' => 'auth',
    ],

    'langs' => ['en' => 'English', 'de' => 'German'],

    /*
  |--------------------------------------------------------------------------
  | Laravel Translations Path
  |--------------------------------------------------------------------------
  |
  | The default is `translations-organizer` but you can change it to whatever works best and
  | doesn't conflict with the routing in your application.
  |
  */
    'path' => env('TRANSLATIONS_PATH', 'translation-organizer'),

    /*
    |--------------------------------------------------------------------------
    | Laravel Translations Custom Domain
    |--------------------------------------------------------------------------
    | You may change the domain where Laravel Translations should be active.
    | If the domain is empty, all domains will be valid.
    |
    */
    'domain' => env('TRANSLATIONS_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Laravel Translations route middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Laravel Translations route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => ['web'],

    /**
     * Enable deletion of translations
     *
     * @type bool
     */
    'delete_enabled' => true,

    /**
     * Exclude specific groups from Laravel Translation Manager.
     * This is useful if, for example, you want to avoid editing the official Laravel language files.
     *
     * @type array
     *
     *    array(
     *        'pagination',
     *        'reminders',
     *        'validation',
     *    )
     */
    'exclude_groups' => [],

    /**
     * Exclude specific languages from Laravel Translation Manager.
     *
     * @type array
     *
     *    array(
     *        'fr',
     *        'de',
     *    )
     */
    'exclude_langs' => [],

    /**
     * Export translations with keys output alphabetically.
     */
    'sort_keys' => false,

    'trans_functions' => [
        'trans',
        'trans_choice',
        'Lang::get',
        'Lang::choice',
        'Lang::trans',
        'Lang::transChoice',
        '@lang',
        '@choice',
        '__',
        '$trans.get',
    ],

    /**
     * Database connection name to allow for different db connection for the translations table.
     */
    'db_connection' => env('TRANSLATION_ORGANIZER_DB_CONNECTION', null),
    'storage.driver' => env('TRANSLATION_ORGANIZER_STORAGE', 'file'),
    'capture_ajax' => false
];
