<x-translation-organizer::layouts.modal>

<style type="text/css">
    #laravel-translation-organizer-modal .translation-btn{
        position: fixed; top: 50%; right: 16px; transform: translateY(-50%);z-index: 999999;background: #fff;padding: 8px;
    }
    #laravel-translation-organizer-modal .overlay-wrapper{
        position: relative;z-index: 99999;
    }
    #laravel-translation-organizer-modal .translation-dialog{
        position: fixed; top: 0;bottom: 0;right: 0; transform: translateX(110%); max-width: 70%; width: 100%;transition: all 0.3s ease-in-out;
    }
    body{
        overflow-x: hidden;
    }
    body.show{
        overflow: hidden;
    }
    body.show #laravel-translation-organizer-modal .translation-dialog{
        transform: translateX(0);
    }
    #laravel-translation-organizer-modal  .overlay-block{
        position:fixed;top: 0;left: 0;right: 0;bottom: 0;background: rgb(107 114 128 / 50%);transition: opacity 0.3s ease-in-out;opacity: 0;visibility: hidden;transition: all 0.3s ease-in-out;
    }
    body.show #laravel-translation-organizer-modal .overlay-block{
        opacity: 1;visibility: visible;
    }
    #laravel-translation-organizer-modal .translation-close-btn{
        position: absolute; top: 16px; left: -40px;
    }
    #laravel-translation-organizer-modal .translation-close-btn button{
        padding: 0.25rem;background: rgb(230 232 234);border-radius: 6px;
    }
    #laravel-translation-organizer-modal .translation-close-btn button:hover{
        color: white;
    }
    #laravel-translation-organizer-modal .translation-close-btn button:focus{
        outline: 2px solid #fff;outline-offset: 2px;
    }
    #laravel-translation-organizer-modal .translation-close-btn button svg{
        width: 24px; height: 24px;
    }
    #laravel-translation-organizer-modal .translation-block{
        display: flex; height: 100%; flex-direction: column; background: #fff; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }
    #laravel-translation-organizer-modal .tb-header{
        display: flex;justify-content: space-between;align-items: center;border-bottom: 1px solid #ddd;padding: 10px 24px;
    }
    #laravel-translation-organizer-modal .tb-header h2{
        color: #111827; font-weight: 500;font-size: 1.125rem;line-height: 1.75rem;
    }
    #laravel-translation-organizer-modal .tb-body{
        padding: 24px;padding-top: 10px;flex: 1 1 0%; overflow-y: auto;
    }
    #laravel-translation-organizer-modal #translation-stop,
    #laravel-translation-organizer-modal #translation-show,
    #laravel-translation-organizer-modal #translation-submit{
        font-weight: 600;padding: 8px 16px;background: #e9f9f0;border-radius: 6px;
    }
    #laravel-translation-organizer-modal .translation-table{
        text-indent: 0;border-color: inherit;border-collapse: collapse;font-weight: 600;background: #fff;
    }
    #laravel-translation-organizer-modal .translation-table > tbody > tr:first-child{
        border-top: 1px solid rgb(230 232 234);
    }
    #laravel-translation-organizer-modal .translation-table > tbody > tr:first-child td:first-child{
        border-top-left-radius: 6px;
    }
    #laravel-translation-organizer-modal .translation-table > tbody > tr:first-child td:last-child{
        border-top-right-radius: 6px;
    }
    #laravel-translation-organizer-modal .translation-table > tbody > tr:last-child td:first-child{
        border-bottom-left-radius: 6px;
    }
    #laravel-translation-organizer-modal .translation-table > tbody > tr:last-child td:last-child{
        border-bottom-right-radius: 6px;
    }
    #laravel-translation-organizer-modal .translation-table > tbody > tr,
    #laravel-translation-organizer-modal .add-translattion-table > tbody > tr{
        border-bottom: 1px solid rgb(230 232 234);
    }
    #laravel-translation-organizer-modal .translation-table > tbody > tr > td,
    #laravel-translation-organizer-modal .add-translattion-table > tbody > tr td{
        color: #000; padding: 8px 16px;border-left: 1px solid rgb(230 232 234);
    }
    #laravel-translation-organizer-modal .add-translattion-table > tbody > tr td{
        border-left: none;
    }
    #laravel-translation-organizer-modal .translation-table > tbody > tr > td:last-child{
        border-right: 1px solid rgb(230 232 234);padding: 0;
    }
    #laravel-translation-organizer-modal .add-translattion-table > tbody > tr:last-child{
        border-bottom: none;
    }
    #laravel-translation-organizer-modal .add-translattion-table > tbody > tr td:last-child{
        border-left: 1px solid rgb(230 232 234);
    }
    #laravel-translation-organizer-modal .bg-gray-100-30{
        background-color:rgb(245 245 245 / 0.3);
    }
    #laravel-translation-organizer-modal .german-trans{
        background: rgb(233 249 240);color: rgb(26 148 75) !important;
    }
    #laravel-translation-organizer-modal .text-gray-700{
        color: rgb(75 85 99) !important;
    }
    #laravel-translation-organizer-modal .text-red-500{
        color: rgb(255 0 0 ) !important ;
    }

</style>
<button class="translation-btn" type="button" onclick="openTranslationDialog()">
    <svg width="60px" fill="#6EBAE7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
        <path
        d="M0 128C0 92.7 28.7 64 64 64H256h48 16H576c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H320 304 256 64c-35.3 0-64-28.7-64-64V128zm320 0V384H576V128H320zM178.3 175.9c-3.2-7.2-10.4-11.9-18.3-11.9s-15.1 4.7-18.3 11.9l-64 144c-4.5 10.1 .1 21.9 10.2 26.4s21.9-.1 26.4-10.2l8.9-20.1h73.6l8.9 20.1c4.5 10.1 16.3 14.6 26.4 10.2s14.6-16.3 10.2-26.4l-64-144zM160 233.2L179 276H141l19-42.8zM448 164c11 0 20 9 20 20v4h44 16c11 0 20 9 20 20s-9 20-20 20h-2l-1.6 4.5c-8.9 24.4-22.4 46.6-39.6 65.4c.9 .6 1.8 1.1 2.7 1.6l18.9 11.3c9.5 5.7 12.5 18 6.9 27.4s-18 12.5-27.4 6.9l-18.9-11.3c-4.5-2.7-8.8-5.5-13.1-8.5c-10.6 7.5-21.9 14-34 19.4l-3.6 1.6c-10.1 4.5-21.9-.1-26.4-10.2s.1-21.9 10.2-26.4l3.6-1.6c6.4-2.9 12.6-6.1 18.5-9.8l-12.2-12.2c-7.8-7.8-7.8-20.5 0-28.3s20.5-7.8 28.3 0l14.6 14.6 .5 .5c12.4-13.1 22.5-28.3 29.8-45H448 376c-11 0-20-9-20-20s9-20 20-20h52v-4c0-11 9-20 20-20z"/>
    </svg>
</button>
<div class="overlay-wrapper" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" id="translation-dialog">
    <div class="overlay-block"></div>
    <div class="translation-dialog">
        <div class="translation-close-btn">
            <button type="button" onclick="closeDialog()">
                <span class="sr-only">Close panel</span>
                <!-- Heroicon name: outline/x-mark -->
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="translation-block">
            <div class="tb-header">
                <h2 id="snide-over-title">Translation
                    Organizer</h2>

                    @if(config('translation-organizer.enabled_on_page'))
                    <button onclick="toggleTranslation('disable')" id="translation-stop">
                    Stop On Page
                </button>
                @else
                <button onclick="toggleTranslation('enable')" id="translation-show">
                Show On Page
            </button>
            @endif

            <button onclick="save()" id="translation-submit">
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
<div class="tb-body">
    <!-- Replace with your content -->
    <div class="tb-body-inner">
        <table class="translation-table">
            <tbody id="translation-html">
                @foreach(\Pinetcodev\LaravelTranslationOrganizer\Services\Translator::getUsedTranslations() as $translation)
                @include('translation-organizer::partials.translation-row', $translation)
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /End replace -->
</div>
</div>

</div>


</div>

<script>
    var color = @json(config("translation-organizer.highlight-color"));
    var langs = @json(array_keys(config("translation-organizer.langs")));
    var url = @json(route("translation_organizer.store"));
    var toggleTranslationURL = @json(route("translation_organizer.toggle"));
    var translationFetch = url;
    var isOnPageTranslationEnable =  @json(config("translation-organizer.enabled_on_page"));
    var csrf = @json(csrf_token());
    var headerName = "laravel-translation-organizer-id";
    var body = document.getElementById("translationBody");
    function openTranslationDialog() {
        const dialog = document.getElementById("translation-dialog")
        // dialog.style.cssText = "z-index: 100000; display: block";
        body.classList.add('show');
    }

    function closeDialog() {
        const dialog = document.getElementById("translation-dialog")
        // dialog.style.cssText = "z-index: 100000; display: none";
        body.classList.remove('show');
    }

    document.onkeydown = function (event) {
        if (event.key === "Escape") {
            closeDialog();
        }
    };


    function markTranslation() {

        if (!isOnPageTranslationEnable) {
            return;
        }

        var translations = document.getElementsByTagName("translation");
        for (var i = 0; i < translations.length; i++) {
            translations[i].style.cssText = 'background: ' + color;
            translations[i].addEventListener("click", event => {
                if (event.altKey) {
                    event.preventDefault();
                    const id = 'translation-' + event.target.dataset.id;
                    openTranslationDialog();
                    const element = document.getElementById(id);
                    setTimeout(function () {
                        element.focus();
                        element.setAttribute('tabindex', "0");
                        element.scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
                    }, 500);
                }
            });
        }

    }

    function toggleTranslation(value) {
        fetch(toggleTranslationURL, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({'value': value})
        }).then((data) => {
            endLoader();
            if (data.ok) {
                location.reload();
            } else {
                alert("Something went wrong")
            }
        });

        location.reload();
    }

    function listenTranslationPaste() {
        const translationLocales = document.querySelectorAll('[data-locale]');
        var translations = document.getElementsByTagName("translation");
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
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify(collections)
        }).then((data) => {
            endLoader();
            if (data.ok) {
                location.reload();
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

    function bindToXHR() {
        var self = this;
        var proxied = XMLHttpRequest.prototype.open;

        XMLHttpRequest.prototype.open = function (method, url, async, user, pass) {
            var xhr = this;
            this.addEventListener("readystatechange", function () {
                var skipUrl = translationFetch + "/fetch/";
                var href = (typeof url === 'string') ? url : url.href;
                if (xhr.readyState == 4 && href.indexOf(skipUrl) !== 0) {
                    handle(xhr);
                }
            }, false);
            proxied.apply(this, Array.prototype.slice.call(arguments));
        };
    }

    function bindToFetch() {
        //   var self = this;
        var proxied = window.fetch;
        if (proxied !== undefined && proxied.polyfill !== undefined) {
            return;
        }

        window.fetch = function () {
            var promise = proxied.apply(this, arguments);
            promise.then(function (response) {
                handle(response);
            });
            return promise;
        };
    }

    function isFetch(response) {
        return Object.prototype.toString.call(response) == '[object Response]'
    }

    function isXHR(response) {
        return Object.prototype.toString.call(response) == '[object XMLHttpRequest]'
    }

    function loadFromId(response) {
        var id = extractIdFromHeaders(response);
        if (id) {
            return id;
        }
        return false;
    }

    function extractIdFromHeaders(response) {
        return getHeader(response, 'laravel-translation-organizer-id');
    }

    function getHeader(response, header) {
        if (isFetch(response)) {
            return response.headers.get(header)
        }
        return response.getResponseHeader(header)
    }

    async function handle(response) {
        // Check if the debugbar header is available
        if (isFetch(response) && !response.headers.has(headerName)) {
            return true;
        } else if (isXHR(response) && response.getAllResponseHeaders().indexOf(headerName) === -1) {
            return true;
        }
        var id = loadFromId(response);
        if (id) {
            var result = await fetch(translationFetch + "/fetch/" + id, {
                method: 'get',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                }
            });

            if (result.ok) {
                var response = await result.json()
                if (response.data) {
                    var translationHtml = document.getElementById('translation-html');
                    for (var i = 0; i < response.data.length; i++) {
                        if (!document.getElementById("translation-" + response.data[i].id)) {
                            translationHtml.innerHTML += response.data[i].html;
                        }
                    }
                }
            } else {
                alert("Something went wrong")
            }

            markTranslation();
        }
        return true;
    }

    function loadFromData(response) {
        var raw = extractDataFromHeaders(response);
        if (!raw) {
            return false;
        }

        var data = parseHeaders(raw);
        if (data.error) {
            throw new Error('Error loading debugbar data: ' + data.error);
        } else if (data.data) {
            alert(data.data);
        }
        return true;
    }

    function parseHeaders(data) {
        return JSON.parse(data);
    }

    function extractDataFromHeaders(response) {
        var data = getHeader(response, 'laravel-translation-organizer-id');
        if (!data) {
            return;
        }
        for (var i = 1; ; i++) {
            var header = getHeader(response, 'laravel-translation-organizer-id' + '-' + i);
            if (!header) {
                break;
            }
            data += header;
        }
        return decodeURIComponent(data);
    }

    listenTranslationPaste();
    bindToXHR();
    bindToFetch();
    markTranslation();

</script>
</x-translation-organizer::layouts.modal>
