<?php

namespace Plank\Contentable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ContentableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('contentable')
            ->hasConfigFile()
            ->hasMigrations([
                'create_contents_table',
                'create_layouts_table',
            ]);
    }
}
