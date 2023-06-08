<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Livewire\Livewire;
use Pinetcodev\LaravelTranslationOrganizer\Http\Livewire\Modals\CreateSourceKey;
use Pinetcodev\LaravelTranslationOrganizer\Http\Livewire\Widgets\ExportTranslations;
use Tests\TestCase as UnitTestCase;
use Pinetcodev\LaravelTranslationOrganizer\LaravelTranslationOrganizerServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends UnitTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Pinetcodev\\LaravelTranslationOrganizer\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTranslationOrganizerServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-translation-organizer_table.php.stub';
        $migration->up();
        */
    }

    public function testKeyIsExistsInDatabase()
    {
        $this->get('/')->assertOk();

        Livewire::test(CreateSourceKey::class)
            ->set('key', 'random text')
            ->set('file', '_json')
            ->call('create');

        $this->assertDatabaseHas('lto-translation', [
            'key' => 'random text'
        ]);
    }

    public function testKeyIsExistsInJsonLanguageFile()
    {
        Livewire::test(CreateSourceKey::class)
            ->set('key', 'random text')
            ->set('file', '_json')
            ->call('create');

        Livewire::test(ExportTranslations::class)->call('export');

        $availableLanguages = config('translation-organizer.langs');

        foreach($availableLanguages as $languageKey => $language) {
            $jsonFilePath = 'lang/'. $languageKey .'.json';

            $this->assertTrue(File::exists($jsonFilePath));

            $jsonFileData = File::get($jsonFilePath);
            $jsonData     = json_decode($jsonFileData, true);

            $this->assertArrayHasKey('random text', $jsonData);
        }
    }

    public function testKeyIsExistsInCustomLanguageFile()
    {
        $file = 'new-file';

        Livewire::test(CreateSourceKey::class)
            ->set('key', 'random text')
            ->set('file', $file)
            ->call('create');

        Livewire::test(ExportTranslations::class)->call('export');

        $availableLanguages = config('translation-organizer.langs');

        foreach($availableLanguages as $languageKey => $language) {
            $jsonFilePath = 'lang/'. $languageKey .'/'. $file .'.php';

            $this->assertTrue(File::exists($jsonFilePath));
            $this->assertTrue(Lang::has($file . '.random text'));
        }
    }

    public function testImportCommand()
    {
        $this->artisan('translation-organizer:import');

        $this->assertDatabaseHas('lto-translation', [
            'group' => 'auth',
            'key'   => 'failed',
            'value' => 'These credentials do not match our records.'
        ]);
    }
}
