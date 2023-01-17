<div class="bg-white fixed bottom-0 right-4 w-96 py-8 px-4 shadow-xl sm:rounded-lg sm:px-10">
    <h2 class="text-xl font-semibold text-gray-900">Translation Manager</h2>

    <form class="space-y-6" action="test" method="POST">
        @csrf
        <input type="hidden" name="route_path" value="{{ request()->path() }}">
        <div>
            <label for="from" class="block text-sm font-medium text-gray-700"> Target Text </label>
            <div class="mt-1">
                <input id="from" name="from" type="text" value="{{ old('from') }}" required
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

        </div>

        <div>
            <label for="to" class="block text-sm font-medium text-gray-700"> Replacement Text </label>
            <div class="mt-1">
                <input id="to" name="to" type="text" value="{{ old('to') }}" required
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

        </div>

        <div>
            <label for="key" class="block text-sm font-medium text-gray-700"> Swap english key text to</label>
            <div class="mt-1">
                <input id="key" name="key" type="text" value="{{ old('key') }}"
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

        </div

        @if(session('translation.saved'))
            <p class="text-green-600 text-sm">{{ session('translation.saved') }}</p>
        @endif

        <div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </div>
    </form>
</div>
<script>
    var color = {!!  json_encode(config("translation-organizer.highlight-color")) !!};
    var translations = document.getElementsByTagName("translation");
    for (var i = 0; i < translations.length; i++) {
        const btn = document.createElement("BUTTON");
       // btn.style.cssText = 'position: relative; top: -1rem; left: -1rem;font-size: 10px ';
     //   btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="height: 15px;width: 15px;"><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/></svg>';
        translations[i].style.cssText = 'background: '+ color;
    }

    function translationHoverIn(element){
        /*var elements = element.getElementsByTagName("svg");
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.cssText = "height: 15px;width: 15px; display: relative"
        }*/
    }

    function translationHoverOut(element){
      /*  setTimeout(function () {
            var elements = element.getElementsByTagName("svg");
            for (var i = 0; i < elements.length; i++) {
                elements[i].style.cssText = "display: none"
            }
        }, 2000)*/

    }

    window.used_translation = "{!! addslashes(json_encode(\Pinetcodev\LaravelTranslationOrganizer\Services\Translator::getUsedTranslations())) !!}";
</script>



