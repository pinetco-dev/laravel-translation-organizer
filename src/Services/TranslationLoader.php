<?php
/**
 * Created by PhpStorm.
 * User: Nilu
 * Date: 9/6/2017
 * Time: 12:20 AM
 */

namespace Pinetcodev\LaravelTranslationOrganizer\Services;


use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\FileLoader;

class TranslationLoader extends FileLoader
{
    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        if ($group === '*' && $namespace === '*') {
            $group = "_json";
        }

        if ($namespace !== null && $namespace !== '*') {
            return $this->loadNamespaced($locale, $group, $namespace);
        }

        //  return Translator::getGroup($group, $locale);

        return Cache::rememberForever("locale.organizer.{$locale}.{$group}",
            function () use ($group, $locale) {
                return Translator::getGroup($group, $locale);
            });
    }
}
