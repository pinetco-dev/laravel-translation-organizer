<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
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

        if (!empty($missionLocales)) {
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
            if (is_array($translation['translations']) && !empty($translation['translations'])) {
                foreach ($translation['translations'] as $locale => $value) {
                    $key = preg_replace("/{$translation['group']}\\./", '', $translation['key'], 1);

                    $result = Translation::where(DB::raw('BINARY `locale`'), $locale)
                        ->where(DB::raw('BINARY `group`'), $translation['group'])
                        ->where(DB::raw('BINARY `key`'), $key)
                        ->first();

                    if (empty($result)) {
                        $result = new Translation();
                        $result->fill([
                            'locale' => $locale,
                            'group' => $translation['group'],
                            'key' => $key,
                        ]);
                    }

                    $result->value = $value;
                    $result->save();
                }
            }
        }
        return response()->json(['data' => [], 'status' => true]);
    }

    public function fetch($requestId)
    {
        $config = config();
        $driver = $config->get('debugbar.storage.driver', 'file');
        $data = cache()->driver($driver)->get($requestId);
        cache()->driver($driver)->delete($requestId);
        $translations = [];
        if ($data && isset($data['translations'])) {
            foreach ($data['translations'] as $translation) {
                $translations[] =
                    [
                        'html' => view('translation-organizer::partials.translation-row', compact('translation'))->render(),
                        'id' => $translation['id']
                    ];
            }
            return response()->json(['data' => $translations, 'status' => true]);
        }
        return response()->json(['data' => [], 'status' => true]);
    }
}
