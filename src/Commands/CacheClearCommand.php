<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Pinetcodev\LaravelTranslationOrganizer\Models\Translation;
use Pinetcodev\LaravelTranslationOrganizer\Services\Manager;
use Symfony\Component\Console\Input\InputOption;

class CacheClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translation-organizer:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear database cache.';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $groups = Translation::whereNotNull('value')->selectDistinctGroup()->get('group');
        foreach ($groups as $group) {
            foreach (array_keys(config("translation-organizer.langs")) as $locale) {
                Cache::forget("locale.organizer.{$locale}.{$group}");
            }
        }
        return self::SUCCESS;
    }

}
