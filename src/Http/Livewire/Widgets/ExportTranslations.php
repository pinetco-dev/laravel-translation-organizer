<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Livewire\Widgets;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Outhebox\LaravelTranslations\TranslationsManager;
use WireUi\Traits\Actions;

class ExportTranslations extends Component
{
    use Actions;

    public function export()
    {
        app(TranslationsManager::class)->export();

        $this->notification()->success('Translations exported successfully!');
    }

    public function render(): View
    {
        return view('translation-organizer::livewire.widgets.export-translations');
    }
}
