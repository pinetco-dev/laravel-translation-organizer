<x-translation-organizer::layouts.modal>
    <button type="button" style="position: fixed; top: 50%; right: 16px; transform: translateY(-50%);
     z-index: 9999" onclick="openTranslationDialog()">
        <svg width="60px" fill="#6EBAE7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
            <path
                d="M0 128C0 92.7 28.7 64 64 64H256h48 16H576c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H320 304 256 64c-35.3 0-64-28.7-64-64V128zm320 0V384H576V128H320zM178.3 175.9c-3.2-7.2-10.4-11.9-18.3-11.9s-15.1 4.7-18.3 11.9l-64 144c-4.5 10.1 .1 21.9 10.2 26.4s21.9-.1 26.4-10.2l8.9-20.1h73.6l8.9 20.1c4.5 10.1 16.3 14.6 26.4 10.2s14.6-16.3 10.2-26.4l-64-144zM160 233.2L179 276H141l19-42.8zM448 164c11 0 20 9 20 20v4h44 16c11 0 20 9 20 20s-9 20-20 20h-2l-1.6 4.5c-8.9 24.4-22.4 46.6-39.6 65.4c.9 .6 1.8 1.1 2.7 1.6l18.9 11.3c9.5 5.7 12.5 18 6.9 27.4s-18 12.5-27.4 6.9l-18.9-11.3c-4.5-2.7-8.8-5.5-13.1-8.5c-10.6 7.5-21.9 14-34 19.4l-3.6 1.6c-10.1 4.5-21.9-.1-26.4-10.2s.1-21.9 10.2-26.4l3.6-1.6c6.4-2.9 12.6-6.1 18.5-9.8l-12.2-12.2c-7.8-7.8-7.8-20.5 0-28.3s20.5-7.8 28.3 0l14.6 14.6 .5 .5c12.4-13.1 22.5-28.3 29.8-45H448 376c-11 0-20-9-20-20s9-20 20-20h52v-4c0-11 9-20 20-20z"/>
        </svg>
    </button>
    <div class="relative mt-8 mb-8" style="z-index: 10000; display: none" aria-labelledby="slide-over-title"
         role="dialog"
         aria-modal="true" id="translation-dialog">
        <!--
          Background backdrop, show/hide based on slide-over state.

          Entering: "ease-in-out duration-500"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in-out duration-500"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-[70%] pl-10">
                    <!--
                      Slide-over panel, show/hide based on slide-over state.

                      Entering: "transform transition ease-in-out duration-500 sm:duration-700"
                        From: "translate-x-full"
                        To: "translate-x-0"
                      Leaving: "transform transition ease-in-out duration-500 sm:duration-700"
                        From: "translate-x-0"
                        To: "translate-x-full"
                    -->
                    <div class="pointer-events-auto relative w-screen">
                        <!--
                          Close button, show/hide based on slide-over state.

                          Entering: "ease-in-out duration-500"
                            From: "opacity-0"
                            To: "opacity-100"
                          Leaving: "ease-in-ou
                          t duration-500"
                            From: "opacity-100"
                            To: "opacity-0"
                        -->
                        <div class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 sm:-ml-10 sm:pr-4">
                            <button type="button" onclick="closeDialog()"
                                    class="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                <span class="sr-only">Close panel</span>
                                <!-- Heroicon name: outline/x-mark -->
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                            <div class="px-4 sm:px-6 flex items-center justify-between">
                                <h2 class="text-lg font-medium text-gray-900" id="snide-over-title">Translation
                                    Organizer</h2>

                                <button onclick="save()" id="translation-submit"
                                        class="flex items-center px-4 py-2 border border-white rounded-md bg-green-300
                                        font-semibold">
                                    <div role="status" id="translation-submit-loader" class="hidden">
                                        <svg aria-hidden="true"
                                             class="w-6 h-6 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-900"
                                             viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                fill="currentColor"/>
                                            <path
                                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                fill="currentFill"/>
                                        </svg>
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    Save
                                </button>
                            </div>
                            <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                <!-- Replace with your content -->
                                <div class="shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                    <table class="min-w-full font-semibold divide-y divide-gray-100">
                                        <tbody class="bg-white divide-y divide-gray-300">
                                        @foreach(\Pinetcodev\LaravelTranslationOrganizer\Services\Translator::getUsedTranslations() as $translation)
                                            <tr class="divide-x divide-gray-300 focus:bg-gray-400"
                                                id="{{$translation['id']}}" translate
                                                data-key="{{$translation['key']}}"
                                                data-group="{{$translation['group']}}">
                                                <td class="relative py-2 pl-4 pr-4 text-black group" width="30%">
                                                    <div class="" data-translation="translation">
                                                        <p> {{ $translation['key'] }}</p>
                                                    </div>
                                                </td>
                                                <td width="70%">
                                                    <table width="100%">
                                                        <tbody class="bg-white divide-y divide-gray-300">
                                                        @foreach($translation["translations"] as $locale => $translationValue )
                                                            @if($loop->index == 0)
                                                                <tr class="divide-x divide-gray-300">
                                                                    <td class="py-2 pl-4 pr-4 text-black bg-gray-100/30"
                                                                        width="25%"> {{$locale}}
                                                                    </td>
                                                                    <td class="py-2 pl-4 pr-4 text-red-500"
                                                                        width="75%" contenteditable="true"
                                                                        data-locale="{{$locale}}"
                                                                    >  {{$translationValue}}</td>
                                                                </tr>
                                                            @else
                                                                <tr class="divide-x divide-gray-300">
                                                                    <td class="py-2 pl-4 pr-4 text-green-700 bg-green-300"
                                                                        width="25%">{{$locale}}</td>
                                                                    <td class="py-2 pl-4 pr-4 text-gray-700"
                                                                        width="75%" contenteditable="true"
                                                                        data-locale="{{$locale}}">{{$translationValue}}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /End replace -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var color = {!!  json_encode(config("translation-organizer.highlight-color")) !!};
        var langs = {!!  json_encode(array_keys(config("translation-organizer.langs"))) !!};
        var url = {!!  json_encode(route("translation_organizer.store")) !!};

        var translations = document.getElementsByTagName("translation");


        function openTranslationDialog() {
            const dialog = document.getElementById("translation-dialog")
            dialog.style.cssText = "z-index: 10000; display: block";
        }

        function closeDialog() {
            const dialog = document.getElementById("translation-dialog")
            dialog.style.cssText = "z-index: 10000; display: none";
        }

        document.onkeydown = function (event) {
            if (event.key === "Escape") {
                closeDialog();
            }
        };


        function markTranslation() {
            for (var i = 0; i < translations.length; i++) {
                const btn = document.createElement("BUTTON");
                translations[i].style.cssText = 'background: ' + color;
                translations[i].addEventListener("click", event => {
                    if (event.altKey) {
                        event.preventDefault();
                        const id = event.target.dataset.id;
                        openTranslationDialog();
                        const element = document.getElementById(id);


                        setTimeout(function () {
                            element.setAttribute('tabindex', '0');
                            element.focus();
                            element.scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
                        }, 500);
                    }
                });
            }
        }

        function listenTranslationPaste() {
            const translationLocales = document.querySelectorAll('[data-locale]');
            for (var i = 0; i < translations.length; i++) {
                translationLocales[i].addEventListener("paste", function (e) {
                    e.preventDefault();

                    if (e.clipboardData) {
                        content = (e.originalEvent || e).clipboardData.getData('text/plain');

                        document.execCommand('insertText', false, content);
                    } else if (window.clipboardData) {
                        content = window.clipboardData.getData('Text');

                        document.selection.createRange().pasteHTML(content);
                    }
                });
            }
        }


        function save() {
            startLoader();
            var elements = document.querySelectorAll('[translate]');
            var collections = [];
            for (var i = 0; i < elements.length; i++) {
                var translation = {};
                translation["key"] = elements[i].dataset.key;
                translation["group"] = elements[i].dataset.group;
                translation["translations"] = {};
                for (var j = 0; j < langs.length; j++) {
                    const newTranslations = elements[i].querySelectorAll('[data-locale]');
                    for (var k = 0; k < newTranslations.length; k++) {
                        translation["translations"][newTranslations[k].dataset.locale] = newTranslations[k].innerHTML.trim();
                    }
                }
                collections.push(translation);
            }

            fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(collections)
            }).then((data) => {
                endLoader();
                if (data.ok) {
                    alert("Updated successfully.");
                } else {
                    alert("Something went wrong")
                }
            });
        }


        function startLoader() {
            document.getElementById('translation-submit').setAttribute("disabled", 1);
            document.getElementById('translation-submit-loader').classList.remove("hidden");
        }

        function endLoader() {
            document.getElementById('translation-submit').removeAttribute("disabled");
            document.getElementById('translation-submit-loader').classList.add("hidden");
        }

        markTranslation();
        listenTranslationPaste();

    </script>
</x-translation-organizer::layouts.modal>


