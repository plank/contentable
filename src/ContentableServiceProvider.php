<?php

namespace Plank\Contentable;

use Plank\Contentable\Commands\ContentMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ContentableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('contentable')
            ->hasCommand(ContentMakeCommand::class)
            ->hasConfigFile()
            ->hasMigrations([
                'create_contents_table',
                'create_layouts_table',
            ]);
    }
}
