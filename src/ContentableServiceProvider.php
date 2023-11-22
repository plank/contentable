<?php

namespace Plank\Contentable;

use Plank\Contentable\Commands\SyncLayouts;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ContentableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('contentable')
            ->hasCommands([
                SyncLayouts::class,
            ])
            ->hasConfigFile()
            ->hasMigrations([
                'create_contents_table',
                'create_layouts_table',
            ]);
    }
}
