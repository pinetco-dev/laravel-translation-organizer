<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;

class TranslationController extends Controller
{
    public function __construct()
    {
    }

    public function index(): View
    {
        return view('translation-organizer::index', [
            'languages_installed' => count(config('translation-organizer.langs')),
        ]);
    }

    public function phrases(Translation $translation)
    {
        return view('translation-organizer::phrases', [
            'translation' => $translation,
        ]);
    }

    public function phrase(Translation $translation)
    {
        $translations = Translation::where('key', $translation->key)
            ->whereIn('locale', array_keys(config('translation-organizer.langs')))
            ->where('group', $translation->group)->get();

        $this->checkAndAddMissingLocale($translations);

        return view('translation-organizer::phrase', [
            'translations' => $translations,
        ]);
    }

    public function checkAndAddMissingLocale(&$translations)
    {
        $missionLocales = $this->getMissingTranslation($translations);

        if (! empty($missionLocales)) {
            foreach ($missionLocales as $locale) {
                $translation = Translation::create([
                    'locale' => $locale,
                    'group' => $translations->first()->group,
                    'key' => $translations->first()->key,
                    'value' => '',
                ]);
                $translations->push($translation);
            }
        }
    }

    public function getMissingTranslation($translations)
    {
        $currentTranslations = $translations->pluck('locale')->toArray();

        return array_diff(array_keys(config('translation-organizer.langs')), $currentTranslations);
    }

    public function store(Request $request)
    {
        $translations = $request->all();
        $groups = collect($translations)->pluck('group')->unique()->toArray();

        foreach ($translations as $translation) {
            if (is_array($translation['translations']) && ! empty($translation['translations'])) {
                foreach ($translation['translations'] as $locale => $value) {
                    $key = preg_replace("/{$translation['group']}\\./", '', $translation['key'], 1);

                    $result = Translation::firstOrCreate([
                        'group' => $translation['group'],
                        'locale' => $locale,
                        'key' => $key,
                    ]);
                    $result->value = $value;
                    $result->save();
                }
            }
        }

        foreach ($groups as $group) {
            foreach (array_keys(config('translation-organizer.langs')) as $locale) {
                cache()->forget(sprintf('locale.organizer.%s.%s',
                    $locale, $group));
            }
        }

        return response()->json(['data' => [], 'status' => true]);
    }
}
