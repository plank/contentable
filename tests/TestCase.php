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
            fn (string $modelName) => 'Plank\\Contentable\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
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

        $migration = include __DIR__.'/Migrations/0000_00_00_000002_create_pages_table.php';
        $migration->up();

        $migration = include __DIR__.'/Migrations/0000_00_00_000003_create_contents_table.php';
        $migration->up();
    }
}
