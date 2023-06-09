<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            @if(isset($translationIfExists) && !$translationIfExists->isEmpty())
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    Translation key is already exists! Do you want to load translation data?
                    <button wire:click="loadTranslationData({{ $translationIfExists }})" class="lex-grow inline-flex items-center justify-center px-4 py-2 font-semibold leading-6 text-sm rounded-md text-white bg-blue-600 hover:bg-blue-700 transition ease-in-out duration-150"> Yes </button>
                </div>
            @endif
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mb-4">
                            <label for="key" class="block text-gray-700 text-sm font-bold mb-2"> Key: </label>
                            <input wire:model.debounce.500ms="key" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="key" placeholder="Enter the key">
                            <p class="text-xs mt-1 text-gray-500"> You can use dot notation (.) to create nested keys. </p>
                            @error('key') <span class="text-red-500"> {{ $message }} </span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="file" class="block text-gray-700 text-sm font-bold mb-2"> File group: </label>
                            <div class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight"> {{ $file }} </div>
                        </div>
                        @foreach($availableLanguages as $languageKey => $language)
                            <div class="mb-4">
                                <label for="translationKey.{{ $languageKey }}" class="block text-gray-700 text-sm font-bold mb-2"> Translation in {{ $language }}: </label>
                                <textarea wire:model="translationKey.{{ $languageKey }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="translationKey.{{ $languageKey }}"  placeholder="Please enter a translation for this key."></textarea>
                                @error('translationKey.' . $languageKey) <span class="text-red-500"> {{ $message }} </span> @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button type="button" wire:click="create" wire:loading.attr="disabled" class="flex-grow inline-flex items-center justify-center px-4 py-2 font-semibold leading-6 text-sm rounded-md text-white bg-blue-600 hover:bg-blue-700 transition ease-in-out duration-150 disabled:cursor-not-allowed">
                            <svg wire:loading wire:target="create" class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:target="create" wire:loading.remove> Add a key </span>
                        </button>
                    </span>
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button type="button" wire:click="$emitUp('closeModel')" class="items-center justify-center px-4 py-2 font-semibold leading-6 text-sm rounded-md text-gray-600 bg-gray-200 hover:bg-gray-300">
                            Cancel
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
