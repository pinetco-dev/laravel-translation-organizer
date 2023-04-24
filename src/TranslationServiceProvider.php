<?php

namespace Pinetcodev\LaravelTranslationOrganizer;

use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;
use Pinetcodev\LaravelTranslationOrganizer\Services\TranslationLoader;
use Pinetcodev\LaravelTranslationOrganizer\Services\Translator;

class TranslationServiceProvider extends BaseTranslationServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('translation-organizer', function ($app) {
            $manager = $app->make('Pinetcodev\LaravelTranslationOrganizer\Services\Manager');

            return $manager;
        });

        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });

        // $this->registerLoader();
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            if ($app->bound('translation-organizer')) {
                $trans->setTranslationManager($app['translation-organizer']);
            }

            return $trans;
        });
    }

}
