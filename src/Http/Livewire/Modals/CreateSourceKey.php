<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;
use Pinetcodev\LaravelTranslationOrganizer\Rules\CheckKeyName;

class CreateSourceKey extends Component
{
    public $key;

    public $file = '_json';

    public array $translationKey = [];

    public $availableLanguages;

    public $fileGroups;

    public $translationIfExists;

    public bool $newFileGroup = false;

    public function mount()
    {
        $this->availableLanguages = config('translation-organizer.langs');
        $this->fileGroups = Translation::groupBy('group')->pluck('group')->toArray();
        $this->translationKey = array_fill_keys(array_keys($this->availableLanguages), '');
    }

    public function rules(): array
    {
        return [
            'key' => [
                'required', new CheckKeyName($this->file),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'key.required' => 'Please enter a key.',
        ];
    }

    public function updatedKey()
    {
        $nameParts = explode('.', $this->key);
        $firstPart = $nameParts[0];

        if (in_array($firstPart, $this->fileGroups)) {
            $this->newFileGroup = true;
            $this->file = $firstPart;
        } elseif (count($nameParts) > 1 && strpos($firstPart, ' ') === false && $firstPart !== ucfirst($firstPart) && (! Str::endsWith($this->key, '.') || Str::substrCount($this->key, '.') > 1)) {
            $this->newFileGroup = true;
            $this->file = 'The given file group is not exits! So we will create one with the name of '.$firstPart;
        } else {
            $this->newFileGroup = false;
            $this->file = '_json';
        }

        $this->key = Str::of($this->key)->trim();

        $this->translationIfExists = Translation::where(DB::raw('BINARY `key`'), $this->newFileGroup ? Str::after($this->key, '.') : $this->key)
            ->where(DB::raw('BINARY `group`'), $this->newFileGroup ? Str::before($this->key, '.') : $this->file)
            ->get(['locale', 'group', 'key', 'value']);

        $this->translationKey = array_fill_keys(array_keys($this->availableLanguages), '');

        $this->validate();
    }

    public function loadTranslationData($translation)
    {
        $translation = collect($translation)->keyBy('locale');

        foreach ($this->availableLanguages as $languageKey => $language) {
            $this->translationKey[$languageKey] = $translation[$languageKey]['value'] ?? null;
        }

        unset($this->translationIfExists);
    }

    public function create()
    {
        $this->validate();

        foreach ($this->availableLanguages as $languageKey => $language) {
            $sourceTranslation = Translation::query()
                ->where(DB::raw('BINARY `key`'), $this->newFileGroup ? Str::after($this->key, '.') : $this->key)
                ->where(DB::raw('BINARY `group`'), $this->newFileGroup ? Str::before($this->key, '.') : $this->file)
                ->where(DB::raw('BINARY `locale`'), $languageKey)
                ->first() ?? new Translation;

            $sourceTranslation->locale = $languageKey;
            $sourceTranslation->group = $this->newFileGroup ? Str::before($this->key, '.') : $this->file;
            $sourceTranslation->key = $this->newFileGroup ? Str::after($this->key, '.') : $this->key;
            $sourceTranslation->value = $this->translationKey[$languageKey];

            $sourceTranslation->save();
        }

        $this->emitUp('closeModel');
    }

    public function render(): View
    {
        return view('translation-organizer::livewire.modals.create-source-key');
    }
}
