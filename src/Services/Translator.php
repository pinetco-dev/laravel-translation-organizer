<?php namespace Pinetcodev\LaravelTranslationOrganizer\Services;


use Illuminate\Support\Arr;
use Illuminate\Translation\Translator as LaravelTranslator;
use Illuminate\Events\Dispatcher;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;

class Translator extends LaravelTranslator
{

    /** @var  Dispatcher */
    protected $events;

    /**
     * Get the translation for the given key.
     *
     * @param string $key
     * @param array $replace
     * @param string $locale
     * @return string
     */
    public function get($key, array $replace = array(), $locale = null, $fallback = true)
    {
        // Get without fallback

        $result = parent::get($key, $replace, $locale, false);
        if ($result === $key) {
            $this->notifyMissingKey($key);

            // Reget with fallback
            $result = parent::get($key, $replace, $locale, $fallback);

        }
        return $result;
    }

    public function setTranslationManager(Manager $manager)
    {
        $this->manager = $manager;
    }

    protected function notifyMissingKey($key)
    {
        list($namespace, $group, $item) = $this->parseKey($key);
        if ($this->manager && $namespace === '*' && $group && $item) {
            $this->manager->missingKey($namespace, $group, $item);
        }
    }

    public static function getGroup($group, $locale): array
    {
        $data = Translation::where('group', $group)->where('locale', $locale)
            ->get()
            ->map(function (Translation $translation) use ($locale, $group) {

                $key = preg_replace("/{$group}\\./", '', $translation->key, 1);
                $value = $translation->value;
                return compact('key', 'value');

            })
            ->pluck('value', 'key')
            ->toArray();

        $data = Arr::undot($data);
        return $data;
    }

}
