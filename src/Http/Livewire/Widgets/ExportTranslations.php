<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Livewire\Widgets;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Pinetcodev\LaravelTranslationOrganizer\Services\Manager;
use WireUi\Traits\Actions;
use function Orchestra\Testbench\artisan;

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
