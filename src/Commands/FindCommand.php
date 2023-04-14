<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Commands;

use Illuminate\Console\Command;
use Pinetcodev\LaravelTranslationOrganizer\Services\Manager;

class FindCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translation-organizer:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find translations in php/twig files';

    /** @var \Pinetcodev\LaravelTranslationOrganizer\Services\Manager */
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $counter = $this->manager->findTranslations(null);
        $this->info('Done importing, processed '.$counter.' items!');
    }
}
