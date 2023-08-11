<?php

namespace Plank\Contentable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Plank\Contentable\Commands\ContentableCommand;

class ContentableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('contentable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_contents_table')
            ->hasCommand(ContentableCommand::class);
    }
}
