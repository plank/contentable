<?php

namespace Plank\Contentable\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Plank\Contentable\ContentableServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Plank\\Contentable\\Tests\\Helper\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->artisan('migrate', [
            '--path' => realpath(__DIR__).'/Helper/Database/Migrations',
            '--realpath' => true,
        ])->run();

        config(['contentable.layouts.namespace' => 'Plank\\Contentable\\Tests\\Helper\\Layouts']);
        config(['contentable.layouts.model' => 'Plank\\Contentable\\Tests\\Helper\\Models\\Layout']);
    }

    protected function getPackageProviders($app)
    {
        return [
            ContentableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/Migrations/0000_00_00_000001_create_fake_module_table.php';
        $migration->up();

        $migration = include __DIR__.'/Migrations/0000_00_00_000003_create_contents_table.php';
        $migration->up();
    }
}
