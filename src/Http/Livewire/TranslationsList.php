<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Http\Livewire;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;

class TranslationsList extends Component
{
    use withPagination;

    public $isOpen = false;

    public $search;

    public $fileGroup;

    public $availableFileGroups;

    protected $listeners = [
        'translationCreated' => '$refresh',
        'closeModel' => 'closeModel',
    ];

    protected $queryString = [
        'search', 'fileGroup',
    ];

    public function mount()
    {
        $this->availableFileGroups = Translation::groupBy('group')->pluck('group')->toArray();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchFileGroup()
    {
        $this->resetPage();
    }

    public function getTranslations(): LengthAwarePaginator
    {
        return Translation::when($this->search, function ($query) {
            $query->where(function ($query) {
                $query->where('key', 'like', "%$this->search%")
                    ->orWhere('value', 'like', "%$this->search%");
            });
        })
            ->when($this->fileGroup, function ($query) {
                $query->where('group', 'like', "%$this->fileGroup%");
            })
            ->groupBy('key')->paginate(12)->onEachSide(0);
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
            Translation::where('key', $translation->key)
                ->where('group', $translation->group)->delete();
            $this->notification()->success('Translation deleted successfully!');
        });
    }

    public function closeModel()
    {
        $this->isOpen = false;
    }

    public function render(): View
    {
        return view('translation-organizer::livewire.translations-list', [
            'translations' => $this->getTranslations(),
        ]);
    }
}
