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
     * @param  array  $replace
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

        self::$pageTranslations[$key] = $result;
        if (is_array($result)) {
            return $result;
        }
        if ($this->manager->isEnable()) {
            return sprintf("<translation data-id='%s'>%s</translation>", $key, $result);
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
        return Arr::undot(Translation::where('group', $group)->where('locale', $locale)
            ->pluck('value', 'key')
            ->toArray());
    }

    public static function getUsedTranslations()
    {
        return self::$pageTranslations;
    }
}
