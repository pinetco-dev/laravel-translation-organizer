<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;

class PhraseForm extends Component
{
    public Translation $translation;

    public function mount(Translation $translation)
    {
        $this->translation = $translation;
    }

    public function rules(): array
    {
        return [
            'translation.value' => 'required',
            'translation.locale' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'translation.value.required' => 'Please enter a translation.',
            'translation.locale.required' => 'Locale is missing.',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->translation->save();

        cache()->forget(sprintf('locale.organizer.%s.%s',
            $this->translation->locale, $this->translation->group));

        /* $nextPhrase = $this->translation
             ->where('id', '>', $this->translation->id)
             ->whereNull('value')
             ->first();

         if ($nextPhrase) {
             $this->redirect(route('translation_organizer.phrases.show', [
                 'phrase' => $nextPhrase,
                 'translation' => $this->translation,
             ]));

             return;
         }

         $this->redirect(route('translation_organizer.phrases.index', $this->translation));*/
    }

    public function missingParameters(): bool
    {
        if (is_array($this->phrase->source->parameters)) {
            foreach ($this->phrase->source->parameters as $parameter) {
                if (! str_contains($this->phrase->value, ":$parameter")) {
                    return true;
                }
            }
        }

        return false;
    }

    public function render(): View
    {
        return view('translation-organizer::livewire.phrase-form');
    }
}
