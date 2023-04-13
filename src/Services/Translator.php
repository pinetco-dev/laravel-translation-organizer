<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Services;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Translation\Translator as LaravelTranslator;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;

class Translator extends LaravelTranslator
{
    /** @var Dispatcher */
    protected $events;

    /**
     * @var Manager
     */
    protected $manager;

    public static array $pageTranslations = [];

    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  string  $locale
     * @return string
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        // Get without fallback

        $result = parent::get($key, $replace, $locale, false);
        if ($result === $key) {
            $this->notifyMissingKey($key);

            // Reget with fallback
            $result = parent::get($key, $replace, $locale, $fallback);
        }

        if (is_array($result)) {
            if ($key === '*') {
                return $result;
            }

            /*  $translationKeys = array_keys(Arr::dot($result));
              foreach ($translationKeys as $translationKey) {
                  self::$pageTranslations[$key] = ['group' => $key, 'item' => $translationKey];
              }*/

            return $result;
        }

        [$namespace, $group, $item] = $this->parseKey($key);
        if (empty($item) || stristr($key, ' ')) {
            self::$pageTranslations[$key] = ['group' => '_json', 'item' => $key];
        } else {
            self::$pageTranslations[$key] = ['group' => $group, 'item' => $item];
        }

        if ($this->manager->isEnable() && $this->manager->isInline()) {
            return sprintf('<translation data-id=%s>%s</translation>', self::generateId($key), $result);
        } else {
            return $result;
        }
    }

    public function setTranslationManager(Manager $manager)
    {
        $this->manager = $manager;
    }

    protected function notifyMissingKey($key)
    {
        [$namespace, $group, $item] = $this->parseKey($key);
        if ($this->manager && $namespace === '*' && $group && $item) {
            $this->manager->missingKey($namespace, $group, $item);
        }
    }

    public static function getGroup($group, $locale): array
    {
        $result = Translation::where('group', $group)->where('locale', $locale)
            ->pluck('value', 'key')
            ->toArray();

        if ($group != '_json') {
            return Arr::undot($result);
        }

        return $result;
    }

    public static function getUsedTranslations()
    {

        $allTranslation = [];
        $groups = Translation::distinct()->get('group')->pluck('group')->toArray();
        $groups[] = '*';

        foreach (self::$pageTranslations as $key => $value) {
            /*$closure = function ($key) {
                $items = explode('.', $key);

                if (count($items) > 1) {
                    $group = array_shift($items);
                } else {
                    $group = Manager::JSON_GROUP;
                }

                $item = implode('.', $items);

                return [$group, $item];
            };
            [$group, $item] = $closure($key);*/

            $group = $value['group'];
            $item = $value['item'];
            if (in_array($item, $groups)) {
                continue;
            }
            $dbTranslation = Translation::where('key', $item)->where('group', $group)->get();

            $langTranslations = [];
            foreach (array_keys(config('translation-organizer.langs')) as $locale) {
                $translation = $dbTranslation->where('locale', $locale)->first();
                $langTranslations[$locale] = $translation ? $translation->value : '';
            }

            $id = self::generateId($key);
            $allTranslation[$id] = ['id' => $id,
                'key' => $key,
                'translations' => $langTranslations, 'group' => $group];
        }

        //   logger(__FUNCTION__, $allTranslation);
        return $allTranslation;
    }

    public static function generateId($title, $separator = '_', $language = 'en', $dictionary = ['@' => 'at'])
    {
        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';

        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Replace dictionary words
        foreach ($dictionary as $key => $value) {
            $dictionary[$key] = $separator.$value.$separator;
        }

        $title = str_replace(array_keys($dictionary), array_values($dictionary), $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }
}
