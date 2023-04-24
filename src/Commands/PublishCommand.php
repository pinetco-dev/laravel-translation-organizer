<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    public $signature = 'translation-organizer:publish {--force : Overwrite any existing files}';

    public $description = 'Publish all of the Translations UI resources';

    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'translation-organizer-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'translation-organizer-migrations',
            '--force' => true,
        ]);

    }
}
