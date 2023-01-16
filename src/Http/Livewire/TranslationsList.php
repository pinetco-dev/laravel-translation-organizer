<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Livewire;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;
use WireUi\Traits\Actions;

class TranslationsList extends Component
{
    use Actions;
    use withPagination;

    public $search;

    protected $listeners = [
        'translationCreated' => '$refresh',
    ];

    public function getTranslations(): LengthAwarePaginator
    {
        $search = $this->search;

        return Translation::when($this->search, function ($query) use ($search) {
            $query->where('key', 'like', "%$search%")
                ->orWhere('group', 'like', "%$search%");
        })->groupBy('key')
            ->paginate(12)->onEachSide(0);
    }

    public function confirmDelete(Translation $translation)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'This action will delete the translation and all phrases, are you sure you want to continue?',
            'method' => 'delete',
            'style' => 'inline',
            'icon' => 'error',
            'params' => $translation,
            'acceptLabel' => 'Yes, delete it',
        ]);
    }

    public function delete(Translation $translation)
    {
        DB::transaction(function () use ($translation) {
            $translation->phrases()->delete();
            $translation->delete();

            $this->notification()->success('Translation deleted successfully!');
        });
    }

    public function render(): View
    {
        return view('translation-organizer::livewire.translations-list', [
            'translations' => $this->getTranslations(),
        ]);
    }
}
