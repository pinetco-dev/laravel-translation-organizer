<x-translation-organizer::layouts.app>
    @foreach($translations as $translation)
        <div class="grid gap-8 grid-cols-1 lg:grid-cols-2 mb-4">
            <div class="col-span-1 bg-white rounded-md overflow-hidden border order-last px-4 divide-y py-4">
                <div class="w-full flex py-2">
                    <div class="ml-3 flex-1 flex-col space-y-1">
                        <span class="text-sm font-medium text-gray-900">Current Version</span>
                        <p class="text-sm text-gray-500">{{ $translation->value }}</p>
                    </div>
                </div>

                <div class="w-full flex py-2">
                    <div class="ml-3 flex-1 flex items-center gap-4 space-y-1">
                        <span class="text-sm font-medium text-gray-900">Key:</span>
                        <p class="text-sm text-gray-500 truncate border px-2 py-1 rounded-md max-w-max"> {{ $translation->key }}</p>
                    </div>
                </div>

                <div class="w-full flex py-2">
                    <div class="ml-3 flex-1 flex items-center gap-4 space-y-1">
                        <span class="text-sm font-medium text-gray-900">File Name:</span>
                        <p class="text-sm text-gray-500 truncate border px-2 py-1 rounded-md max-w-max">{{ \Str::replace('_', $translation->locale . '.', $translation->group) }}</p>
                    </div>
                </div>
            </div>
            @livewire('translations-organizer::phrase-form', ['translation' => $translation])
        </div>
    @endforeach
</x-translation-organizer::layouts.app>
