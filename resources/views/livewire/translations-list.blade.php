<div class="rounded-lg bg-white px-5 py-6 shadow sm:px-6">
    <div class="sm:flex sm:items-center">
        <div class="flex items-center sm:flex-auto space-x-3">
            <h1 class="text-xl font-semibold text-gray-900">Translations</h1>
        </div>

        <div class="mt-4 mr-4 sm:mt-0 sm:ml-16 flex flex-col md:flex-row space-y-4 md:space-y-0 gap-4 w-full max-w-2xl">
            <div class="relative mt-4 sm:mt-0 w-full">
                <input wire:model="search" icon="search" type="search"
                       placeholder="Search with Key or Translation"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       title="Search with Key or Translation"
                />
            </div>

            <div class="relative mt-4 sm:mt-0 w-full">
                <select wire:model="fileGroup" id="" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" title="Select file type">
                    <option value=""> --- Select file type --- </option>
                    @foreach($availableFileGroups as $group)
                        <option value="{{ $group }}"> {{ $group }} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <button wire:click="$set('isOpen', true)" class="flex items-center justify-center px-4 py-2 border border-white rounded-md bg-violet-600 hover:bg-violet-500 w-full max-w-40 disabled:cursor-not-allowed">
            <span class="text-white font-medium"> Add new translation </span>
        </button>
        @if($isOpen)
            @livewire('translations-organizer::create-source-key-modal')
        @endif
    </div>

    <div class="mt-6 flex flex-col">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg overflow-x-auto">
                <div class="min-w-full divide-y divide-gray-300">
                    <div class="bg-gray-50 flex items-center">
                        <div class="w-1/5 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                            Key
                        </div>
                        <div class="w-1/2 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                            Translation
                        </div>
                        <div class="w-1/5  py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                            Group
                        </div>
                        <div class="w-10 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                            Action
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200 bg-white">
                        @foreach($translations as $translation)
                            <div class="hover:bg-gray-50 cursor-pointer relative flex">
                                <div class="w-full py-3 pl-4 pr-3 text-sm sm:pl-6">
                                    <div class="w-full flex items-center flex-wrap">
                                        <div
                                            class="w-1/5  pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            {{ $translation->key }}
                                        </div>
                                        <div
                                            class="w-1/2  pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            {{ $translation->value ?: $translation->key  }}
                                        </div>
                                        <div
                                            class="w-1/5  pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            {{ $translation->group }}
                                        </div>
                                        <div
                                            class="w-10  pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 flex flex-row">
                                            <a href="{{ route('translation_organizer.show', $translation) }}"
                                               class="text-gray-400 hover:text-violet-700 ml-auto relative z-50"
                                               title="Edit key"
                                            >
                                                <x-translation-organizer::icons.translate class="w-5 h-5"/>
                                            </a>
                                            @if(! $translation->source)
                                                <button wire:click="confirmDelete({{ $translation->id }})"
                                                        class="text-gray-400 hover:text-red-500 relative z-50"
                                                        title="Delete key"
                                                >
                                                    <x-translation-organizer::icons.trash class="w-5 h-5"/>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($translations->hasPages())
                    <div class="px-6 border-t py-4">
                        {{ $translations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
