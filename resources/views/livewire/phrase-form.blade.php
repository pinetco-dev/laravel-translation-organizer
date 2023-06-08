<div class="col-span-1 bg-white border group rounded-md overflow-hidden">
    <div class="flex items-center border-b space-x-2 px-3 py-2">
        <div class="flex items-center space-x-2">
            <div
                class="text-sm font-semibold text-gray-800">{{ config("translation-organizer.langs")[$translation->locale] }}</div>
            <div class="text-xs text-gray-500 border rounded-md px-1.5 py-0.5">{{ $translation->locale }}</div>
            @if(!$translation->value)
                <div class="text-sm font-semibold text-yellow-600">
                    <x-translation-organizer::icons.warning class="w-5 h-5"/>
                </div>
            @endif
        </div>
    </div>
    <div class="w-full p-3">
        <textarea id="textArea" dir="auto" wire:model="translation.value"
                  class="w-full min-h-36 without-ring resize-none border-0 m-0 p-0"></textarea>
        @error('translation.value') <span class="text-red-500"> {{ $message }} </span> @enderror
    </div>
    <div class="w-full grid grid-cols-2 border-t gap-6 px-4 py-3">
        <a href="{{ route('translation_organizer.index') }}"
           class="text-sm font-medium text-center w-full border border-violet-400 text-violet-700 hover:bg-violet-50 py-3 rounded-md uppercase">
            Cancel
        </a>
        <button wire:click="save"
                class="text-sm font-medium w-full bg-violet-700 hover:bg-violet-500 text-white py-3 rounded-md uppercase">
            Save Changes
        </button>
    </div>
</div>
