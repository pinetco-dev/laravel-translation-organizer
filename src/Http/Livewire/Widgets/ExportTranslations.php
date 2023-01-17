<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Livewire\Widgets;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Pinetcodev\LaravelTranslationOrganizer\Services\Manager;
use WireUi\Traits\Actions;

class ExportTranslations extends Component
{
    use Actions;

    public function export()
    {
        $manager = resolve(Manager::class);
        $manager->exportAllTranslations();
        $this->notification()->success('Translations exported successfully!');
    }

    public function render(): View
    {
        return view('translation-organizer::livewire.widgets.export-translations');
    }
}
