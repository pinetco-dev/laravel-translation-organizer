<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Pinetcodev\LaravelTranslationOrganizer\Events\TranslationsExportedEvent;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Manager
{
    const JSON_GROUP = '_json';

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $files;

    /** @var \Illuminate\Contracts\Events\Dispatcher */
    protected $events;

    protected $config;

    protected $locales;

    protected $ignoreLocales;

    protected $ignoreFilePath;

    protected $requestId;

    protected $data;

    public function __construct(Application $app, Filesystem $files, Dispatcher $events)
    {
        $this->app = $app;
        $this->files = $files;
        $this->events = $events;
        $this->config = $app['config']['translation-organizer'];
        $this->ignoreFilePath = storage_path('.ignore_locales');
        $this->locales = [];
        $this->ignoreLocales = $this->getIgnoredLocales();
    }

    protected function getIgnoredLocales()
    {
        if (! $this->files->exists($this->ignoreFilePath)) {
            return [];
        }
        $result = json_decode($this->files->get($this->ignoreFilePath));

        return ($result && is_array($result)) ? $result : [];
    }

    public function importTranslations($replace = false, $base = null, $import_group = false)
    {
        $counter = 0;
        //allows for vendor lang files to be properly recorded through recursion.
        $vendor = true;
        if ($base == null) {
            $base = $this->app['path.lang'];
            $vendor = false;
        }

        foreach ($this->files->directories($base) as $langPath) {
            $locale = basename($langPath);

            //import langfiles for each vendor
            if ($locale == 'vendor') {
                foreach ($this->files->directories($langPath) as $vendor) {
                    $counter += $this->importTranslations($replace, $vendor);
                }

                continue;
            }
            $vendorName = $this->files->name($this->files->dirname($langPath));
            foreach ($this->files->allfiles($langPath) as $file) {
                $info = pathinfo($file);
                $group = $info['filename'];
                if ($import_group) {
                    if ($import_group !== $group) {
                        continue;
                    }
                }

                if (in_array($group, $this->config['exclude_groups'])) {
                    continue;
                }
                $subLangPath = str_replace($langPath.DIRECTORY_SEPARATOR, '', $info['dirname']);
                $subLangPath = str_replace(DIRECTORY_SEPARATOR, '/', $subLangPath);
                $langPath = str_replace(DIRECTORY_SEPARATOR, '/', $langPath);

                if ($subLangPath != $langPath) {
                    $group = $subLangPath.'/'.$group;
                }

                if (! $vendor) {
                    $translations = \Lang::getLoader()->load($locale, $group);
                } else {
                    $translations = include $file;
                    $group = 'vendor/'.$vendorName;
                }

                if ($translations && is_array($translations)) {
                    foreach (Arr::dot($translations) as $key => $value) {
                        $importedTranslation = $this->importTranslation($key, $value, $locale, $group, $replace);
                        $counter += $importedTranslation ? 1 : 0;
                    }
                }
            }
        }

        foreach ($this->files->files($this->app['path.lang']) as $jsonTranslationFile) {
            if (strpos($jsonTranslationFile, '.json') === false) {
                continue;
            }
            $locale = basename($jsonTranslationFile, '.json');
            $group = self::JSON_GROUP;
            $translations =
                \Lang::getLoader()->load($locale, '*', '*'); // Retrieves JSON entries of the given locale only

            if ($translations && is_array($translations)) {
                foreach ($translations as $key => $value) {
                    $importedTranslation = $this->importTranslation($key, $value, $locale, $group, $replace);
                    $counter += $importedTranslation ? 1 : 0;
                }
            }
        }

        return $counter;
    }

    public function importTranslation($key, $value, $locale, $group, $replace = false)
    {
        // process only string values
        if (is_array($value)) {
            return false;
        }

        $value = (string) $value;

        $translation = Translation::where(DB::raw('BINARY `locale`'), $locale)
            ->where(DB::raw('BINARY `group`'), $group)
            ->where(DB::raw('BINARY `key`'), $key)
            ->first();

        if (empty($translation)) {
            $translation = new Translation();
            $translation->fill([
                'locale' => $locale,
                'group' => $group,
                'key' => $key,
            ]);
        }

        // Check if the database is different then the files
        $newStatus = $translation->value === $value ? Translation::STATUS_SAVED : Translation::STATUS_CHANGED;
        if ($newStatus !== (int) $translation->status) {
            $translation->status = $newStatus;
        }

        // Only replace when empty, or explicitly told so
        if ($replace || ! $translation->value) {
            $translation->value = $value;
        }

        $translation->save();

        return true;
    }

    public function findTranslations($path = null)
    {
        $path = $path ?: base_path();
        $groupKeys = [];
        $stringKeys = [];
        $functions = $this->config['trans_functions'];

        $groupPattern =                          // See https://regex101.com/r/WEJqdL/6
            "[^\w|>]".                          // Must not have an alphanum or _ or > before real method
            '('.implode('|', $functions).')'.  // Must start with one of the functions
            "\(".                               // Match opening parenthesis
            "[\'\"]".                           // Match " or '
            '('.                                // Start a new group to match:
            '[\/a-zA-Z0-9_-]+'.                 // Must start with group
            "([.](?! )[^\1)]+)+".               // Be followed by one or more items/keys
            ')'.                                // Close group
            "[\'\"]".                           // Closing quote
            "[\),]";                             // Close parentheses or new parameter

        $stringPattern =
            "[^\w]".                                     // Must not have an alphanum before real method
            '('.implode('|', $functions).')'.             // Must start with one of the functions
            "\(\s*".                                       // Match opening parenthesis
            "(?P<quote>['\"])".                            // Match " or ' and store in {quote}
            "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)". // Match any string that can be {quote} escaped
            "\k{quote}".                                   // Match " or ' previously matched
            "\s*[\),]";                                    // Close parentheses or new parameter

        // Find all PHP + Twig files in the app folder, except for storage
        $finder = new Finder();
        $finder->in($path)->exclude('storage')->exclude('vendor')->name('*.php')->name('*.twig')->name('*.vue')->files();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            // Search the current file for the pattern
            if (preg_match_all("/$groupPattern/siU", $file->getContents(), $matches)) {
                // Get all matches
                foreach ($matches[2] as $key) {
                    $groupKeys[] = $key;
                }
            }

            if (preg_match_all("/$stringPattern/siU", $file->getContents(), $matches)) {
                foreach ($matches['string'] as $key) {
                    if (preg_match("/(^[\/a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) {
                        // group{.group}.key format, already in $groupKeys but also matched here
                        // do nothing, it has to be treated as a group
                        continue;
                    }

                    //TODO: This can probably be done in the regex, but I couldn't do it.
                    //skip keys which contain namespacing characters, unless they also contain a
                    //space, which makes it JSON.
                    if (! (Str::contains($key, '::') && Str::contains($key, '.'))
                        || Str::contains($key, ' ')) {
                        $stringKeys[] = $key;
                    }
                }
            }
        }
        // Remove duplicates
        $groupKeys = array_unique($groupKeys);
        $stringKeys = array_unique($stringKeys);

        // Add the translations to the database, if not existing.
        foreach ($groupKeys as $key) {
            // Split the group and item
            [$group, $item] = explode('.', $key, 2);
            $this->missingKey('', $group, $item);
        }

        foreach ($stringKeys as $key) {
            $group = self::JSON_GROUP;
            $item = $key;
            $this->missingKey('', $group, $item);
        }

        // Return the number of found translations
        return count($groupKeys + $stringKeys);
    }

    public function missingKey($namespace, $group, $key)
    {
        if (! in_array($group, $this->config['exclude_groups'])) {
            foreach (array_keys($this->config['langs']) as $locale) {
                $result = Translation::where(DB::raw('BINARY `locale`'), $locale)
                    ->where(DB::raw('BINARY `group`'), $group)
                    ->where(DB::raw('BINARY `key`'), $key)
                    ->first();

                if (empty($result)) {
                    Translation::create([
                        'locale' => $locale,
                        'group' => $group,
                        'key' => $key,
                    ]);
                }
            }
        }
    }

    public function exportTranslations($group = null, $json = false)
    {
        $group = basename($group);
        $basePath = $this->app['path.lang'];

        if (! is_null($group) && ! $json) {
            if (! in_array($group, $this->config['exclude_groups'])) {
                $vendor = false;
                if ($group == '*') {
                    return $this->exportAllTranslations();
                } else {
                    if (Str::startsWith($group, 'vendor')) {
                        $vendor = true;
                    }
                }

                $tree = $this->makeTree(Translation::ofTranslatedGroup($group)
                    ->orderByGroupKeys(Arr::get($this->config, 'sort_keys', false))
                    ->get());

                foreach ($tree as $locale => $groups) {
                    $locale = basename($locale);
                    if (isset($groups[$group])) {
                        $translations = $groups[$group];
                        $path = $this->app['path.lang'];

                        $locale_path = $locale.DIRECTORY_SEPARATOR.$group;
                        if ($vendor) {
                            $path = $basePath.'/'.$group.'/'.$locale;
                            $locale_path = Str::after($group, '/');
                        }
                        $subfolders = explode(DIRECTORY_SEPARATOR, $locale_path);
                        array_pop($subfolders);

                        $subfolder_level = '';
                        foreach ($subfolders as $subfolder) {
                            $subfolder_level = $subfolder_level.$subfolder.DIRECTORY_SEPARATOR;

                            $temp_path = rtrim($path.DIRECTORY_SEPARATOR.$subfolder_level, DIRECTORY_SEPARATOR);
                            if (! is_dir($temp_path)) {
                                mkdir($temp_path, 0777, true);
                            }
                        }

                        if ($vendor) {
                            $path = $path.DIRECTORY_SEPARATOR.'messages.php';
                        } else {
                            $path = $path.DIRECTORY_SEPARATOR.$locale.DIRECTORY_SEPARATOR.$group.'.php';
                        }

                        $output = "<?php\n\nreturn ".$this->varExport($translations, true).';'.\PHP_EOL;
                        $this->files->put($path, $output);
                        Cache::forget("locale.organizer.{$locale}.{$group}");
                    }
                }
                Translation::ofTranslatedGroup($group)->update(['status' => Translation::STATUS_SAVED]);
            }
        }

        if ($json) {
            $tree = $this->makeTree(Translation::ofTranslatedGroup(self::JSON_GROUP)
                ->orderByGroupKeys(Arr::get($this->config, 'sort_keys', false))
                ->get(), true);

            foreach ($tree as $locale => $groups) {
                if (isset($groups[self::JSON_GROUP])) {
                    $translations = $groups[self::JSON_GROUP];
                    $path = $this->app['path.lang'].'/'.$locale.'.json';
                    $output = json_encode($translations, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE);
                    $this->files->put($path, $output);
                    Cache::forget("locale.organizer.{$locale}.{$group}");
                }
            }

            Translation::ofTranslatedGroup(self::JSON_GROUP)->update(['status' => Translation::STATUS_SAVED]);
        }

        $this->events->dispatch(new TranslationsExportedEvent());
    }

    public function exportAllTranslations()
    {
        $groups = Translation::whereNotNull('value')->selectDistinctGroup()->get('group');

        foreach ($groups as $group) {
            if ($group->group == self::JSON_GROUP) {
                $this->exportTranslations(null, true);
            } else {
                $this->exportTranslations($group->group);
            }
        }

        $this->events->dispatch(new TranslationsExportedEvent());
    }

    protected function makeTree($translations, $json = false)
    {
        $array = [];
        foreach ($translations as $translation) {
            if ($json) {
                $this->jsonSet($array[$translation->locale][$translation->group], $translation->key,
                    $translation->value);
            } else {
                Arr::set($array[$translation->locale][$translation->group], $translation->key,
                    $translation->value);
            }
        }

        return $array;
    }

    public function jsonSet(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $array[$key] = $value;

        return $array;
    }

    public function cleanTranslations()
    {
        Translation::whereNull('value')->delete();
    }

    public function truncateTranslations()
    {
        Translation::truncate();
    }

    public function getLocales()
    {
        if (empty($this->locales)) {
            $locales = array_merge([config('app.locale')],
                Translation::groupBy('locale')->pluck('locale')->toArray());
            foreach ($this->files->directories($this->app->langPath()) as $localeDir) {
                if (($name = $this->files->name($localeDir)) != 'vendor') {
                    $locales[] = $name;
                }
            }

            $this->locales = array_unique($locales);
            sort($this->locales);
        }

        return array_diff($this->locales, $this->ignoreLocales);
    }

    public function addLocale($locale)
    {
        $localeDir = $this->app->langPath().'/'.basename($locale);

        $this->ignoreLocales = array_diff($this->ignoreLocales, [$locale]);
        $this->saveIgnoredLocales();
        $this->ignoreLocales = $this->getIgnoredLocales();

        if (! $this->files->exists($localeDir) || ! $this->files->isDirectory($localeDir)) {
            return $this->files->makeDirectory($localeDir);
        }

        return true;
    }

    protected function saveIgnoredLocales()
    {
        return $this->files->put($this->ignoreFilePath, json_encode($this->ignoreLocales));
    }

    public function removeLocale($locale)
    {
        if (! $locale) {
            return false;
        }
        $this->ignoreLocales = array_merge($this->ignoreLocales, [$locale]);
        $this->saveIgnoredLocales();
        $this->ignoreLocales = $this->getIgnoredLocales();

        Translation::where('locale', $locale)->delete();
    }

    public function getConfig($key = null)
    {
        if ($key == null) {
            return $this->config;
        } else {
            return $this->config[$key];
        }
    }

    public function isEnable()
    {
        $config = config();
        $driver = $config->get('translation-organizer.session.driver', 'file');

        return session()->driver($driver)->get('TRANSLATION_ENABLED', false);
    }

    public function isInline()
    {
        $config = config();
        $driver = $config->get('translation-organizer.session.driver', 'file');

        return session()->driver($driver)->get('TRANSLATION_ON_PAGE', false);
    }

    /**
     * Modify the response and inject the translation model (or data in headers)
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifyResponse($request, $response)
    {
        if ($response->isRedirection()) {
            return $response;
        } elseif ($this->isJsonRequest($request)) {
            $content = $response->getContent();
            $response->setContent($this->parseTags($content));

            return $response;
        } elseif (
            ($response->headers->has('Content-Type') &&
                strpos($response->headers->get('Content-Type'), 'html') === false) ||
            $request->getRequestFormat() !== 'html' ||
            $response->getContent() === false ||
            ($request->headers->get('X-Livewire') && $this->isJsonRequest($request))
        ) {
            try {
                // Just collect + store data, don't inject it.
                $this->collect();
                if ($request->headers->get('X-Livewire')) {
                    $response->headers->set('laravel-translation-organizer-id', $this->requestId);
                }
                $content = $response->getContent();
                $response->setContent($this->parseTags($content));

                return $response;
            } catch (\Exception $e) {
                // catch the errors
            }
        } else {
            $this->injectTranslation($response);
        }

    }

    /**
     * @return bool
     */
    protected function isJsonRequest(Request $request)
    {
        // If XmlHttpRequest or Live, return true
        if ($request->isXmlHttpRequest()) {
            return true;
        }

        // Check if the request wants Json
        $acceptable = $request->getAcceptableContentTypes();

        return isset($acceptable[0]) && $acceptable[0] == 'application/json';
    }

    protected function injectTranslation(Response $response)
    {
        $content = $response->getContent();

        $widget = $this->render();
        $pos = strripos($content, '</body>');
        if (false !== $pos) {
            $content = substr($content, 0, $pos).$widget.substr($content, $pos);
        } else {
            $content = $content.$widget;
        }

        $original = null;
        if ($response instanceof \Illuminate\Http\Response && $response->getOriginalContent()) {
            $original = $response->getOriginalContent();
        }

        //     $content = preg_replace('/&lt;translation/', "<translation", $content);
        //    $content = preg_replace("/&lt;[\/]{1}translation&gt;[ ]{0,}[\n]{0,}/", "</translation>", $content);
        //  $content = preg_replace('/"[ ]{0,}[\n]{0,}<translation/', "<translation", $content);
        //  $content = preg_replace('/<\/translation>[ ]{0,}[\n]{0,}"/', "</translation>", $content);

        // Update the new content and reset the content length
        $response->setContent($this->parseTags($content));
        $response->headers->remove('Content-Length');

        // Restore original response (eg. the View or Ajax data)
        if ($original) {
            $response->original = $original;
        }
    }

    protected function render()
    {
        $isTranslationEnable = $this->isEnable();
        $isPageTranslationEnable = $this->isInline();

        return view('translation-organizer::translation-organizer', compact('isTranslationEnable', 'isPageTranslationEnable'));
    }

    public function varExport($expression, $return = false)
    {
        $export = var_export($expression, true);
        $export = preg_replace('/^([ ]*)(.*)/m', '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
        $export = implode(PHP_EOL, array_filter(['['] + $array));
        if ((bool) $return) {
            return $export;
        } else {
            echo $export;
        }
    }

    public function collect()
    {
        /** @var Request $request */
        $request = $this->app['request'];

        $requestId = $this->getCurrentRequestId();
        $this->data = [
            '__meta' => [
                'id' => $requestId,
                'datetime' => date('Y-m-d H:i:s'),
                'utime' => microtime(true),
                'method' => $request->getMethod(),
                'uri' => $request->getRequestUri(),
                'ip' => $request->getClientIp(),
            ],
        ];

        // Remove all invalid (non UTF-8) characters
        array_walk_recursive(
            $this->data,
            function (&$item) {
                if (is_string($item) && ! mb_check_encoding($item, 'UTF-8')) {
                    $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
                }
            }
        );

        $this->data['translations'] = Translator::getUsedTranslations();
        $this->storeData();

        return $this->data;
    }

    public function getCurrentRequestId()
    {
        if ($this->requestId === null) {
            $this->requestId = Str::uuid();
        }

        return $this->requestId;
    }

    protected function storeData()
    {
        $config = $this->app['config'];
        //if ($config->get('translation-organizer.enabled')) {
        $driver = $config->get('translation-organizer.storage.driver', 'file');
        cache()->driver($driver)->set($this->requestId, $this->data);
        //        }
    }

    public function parseTags($content)
    {
        $content = preg_replace('/&lt;/', '<', $content);
        $content = preg_replace('/&gt;/', '>', $content);
        $content = preg_replace('/&amp;lt;/', '<', $content);

        return preg_replace('/&amp;gt;/', '>', $content);

    }

    public function replaceSlash()
    {
        $base = $this->app['path.lang'];

        $files = File::allFiles($base);

        foreach ($files as $file) {
            $contents = file_get_contents($file);
            $contents = str_replace('\/', '/', $contents);
            file_put_contents($file, $contents);
        }
    }
}
