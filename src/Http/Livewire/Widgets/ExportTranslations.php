<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Livewire\Widgets;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Pinetcodev\LaravelTranslationOrganizer\Services\Manager;

class ExportTranslations extends Component
{
    public function export()
    {
        $manager = resolve(Manager::class);
        $manager->exportAllTranslations();
    }

    public function render(): View
    {
        return view('translation-organizer::livewire.widgets.export-translations');
    }
}
