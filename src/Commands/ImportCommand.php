<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Commands;

use Illuminate\Console\Command;
use Pinetcodev\LaravelTranslationOrganizer\Services\Manager;
use Symfony\Component\Console\Input\InputOption;

class ImportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translation-organizer:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import translations from the PHP sources';

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
    public function handle(): int
    {
        $replace = $this->option('replace');
        $counter = $this->manager->importTranslations($replace);
        $this->info('Done importing, processed ' . $counter . ' items!');
        return self::SUCCESS;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['replace', 'R', InputOption::VALUE_NONE, 'Replace existing keys'],
        ];
    }
}
